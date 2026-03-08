<?php
// app/Models/Product.php

namespace App\Models;

class Product extends Model {
    protected $table = 'mahsulotlar';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kategoriya_id', 'subkategoriya_id', 'nomi', 'shtrix_kod', 
        'birlik', 'kelish_narxi', 'sotish_narxi', 'miqdor', 
        'minimal_miqdor', 'faol'
    ];
    
    // Kategoriya bilan bog'lanish
    public function category() {
        $categoryModel = new Category();
        return $categoryModel->find($this->kategoriya_id);
    }
    
    // Subkategoriya bilan bog'lanish
    public function subcategory() {
        if ($this->subkategoriya_id) {
            $subcategoryModel = new Subcategory();
            return $subcategoryModel->find($this->subkategoriya_id);
        }
        return null;
    }
    
    // Shtrix kod bo'yicha qidirish
    public function findByBarcode($barcode) {
        $stmt = $this->db->prepare("
            SELECT m.*, k.nomi as kategoriya_nomi, s.nomi as subkategoriya_nomi
            FROM {$this->table} m
            LEFT JOIN kategoriyalar k ON m.kategoriya_id = k.id
            LEFT JOIN subkategoriyalar s ON m.subkategoriya_id = s.id
            WHERE m.shtrix_kod = ? AND m.ochirilgan_vaqt IS NULL
        ");
        $stmt->execute([$barcode]);
        return $stmt->fetch();
    }
    
    // Qoldigi kam mahsulotlar
    public function lowStock() {
        $stmt = $this->db->prepare("
            SELECT m.*, k.nomi as kategoriya_nomi
            FROM {$this->table} m
            LEFT JOIN kategoriyalar k ON m.kategoriya_id = k.id
            WHERE m.ochirilgan_vaqt IS NULL 
            AND m.faol = 1 
            AND m.miqdor <= m.minimal_miqdor
            ORDER BY (m.minimal_miqdor - m.miqdor) DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Kategoriya bo'yicha mahsulotlar
    public function getByCategory($categoryId) {
        $stmt = $this->db->prepare("
            SELECT m.*, s.nomi as subkategoriya_nomi
            FROM {$this->table} m
            LEFT JOIN subkategoriyalar s ON m.subkategoriya_id = s.id
            WHERE m.kategoriya_id = ? AND m.ochirilgan_vaqt IS NULL
            ORDER BY m.nomi ASC
        ");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }
    
    // Qidirish (nomi yoki shtrix kod bo'yicha)
 /**
 * Qidirish (nomi yoki shtrix kod bo'yicha)
 */
public function search($keyword, $limit = 20) {
            // LIMIT ni to'g'ridan-to'g'ri so'rovga qo'shamiz
        $stmt = $this->db->prepare("
                SELECT m.*, 
                    k.nomi as kategoriya_nomi, 
                    s.nomi as subkategoriya_nomi,
                    (SELECT COUNT(*) FROM savdo_tarkibi st WHERE st.mahsulot_id = m.id) as sotilgan_soni
                FROM {$this->table} m
                LEFT JOIN kategoriyalar k ON m.kategoriya_id = k.id
                LEFT JOIN subkategoriyalar s ON m.subkategoriya_id = s.id
                WHERE m.ochirilgan_vaqt IS NULL 
                AND (m.nomi LIKE ? OR m.shtrix_kod LIKE ?)
                AND m.faol = 1
                ORDER BY 
                    CASE 
                        WHEN m.shtrix_kod = ? THEN 1
                        WHEN m.nomi LIKE ? THEN 2
                        ELSE 3
                    END,
                    m.nomi ASC
                LIMIT " . intval($limit) . "
            ");
            
            $searchTerm = "%{$keyword}%";
            $stmt->execute([
                $searchTerm, 
                $searchTerm, 
                $keyword, 
                $searchTerm
            ]);
            
            return $stmt->fetchAll();
        }
    // Narxni yangilash
    public function updatePrice($id, $newPrice, $userId) {
        $product = $this->find($id);
        if (!$product) return false;
        
        $oldPrice = $product['sotish_narxi'];
        
        $this->db->beginTransaction();
        
        try {
            // Narxni yangilash
            $this->update($id, ['sotish_narxi' => $newPrice]);
            
            // Narx o'zgarishi tarixiga yozish (agar jadval bo'lsa)
            // $priceHistoryModel = new PriceHistory();
            // $priceHistoryModel->create([...]);
            
            $this->db->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    // Miqdorni yangilash (ombor jurnaliga yozish bilan)
    public function updateStock($id, $newQuantity, $userId, $reason = 'SOZLASH', $manbaTuri = 'SOZLASH', $manbaId = null) {
        $product = $this->find($id);
        if (!$product) return false;
        
        $oldQuantity = $product['miqdor'];
        $change = $newQuantity - $oldQuantity;
        
        $this->db->beginTransaction();
        
        try {
            // Mahsulot miqdorini yangilash
            $this->update($id, ['miqdor' => $newQuantity]);
            
            // Ombor jurnaliga yozish
            $journalModel = new WarehouseJournal();
            $journalModel->create([
                'mahsulot_id' => $id,
                'amal' => $change > 0 ? 'KIRIM' : 'CHIQIM',
                'miqdor_ozgarish' => $change,
                'eski_miqdor' => $oldQuantity,
                'yangi_miqdor' => $newQuantity,
                'manba_turi' => $manbaTuri,
                'manba_id' => $manbaId,
                'foydalanuvchi_id' => $userId,
                'izoh' => $reason
            ]);
            
            $this->db->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    // Mahsulot statistikasi
     public function getStats($id) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(DISTINCT s.id) as jami_sotuvlar,
                IFNULL(SUM(st.soni), 0) as jami_sotilgan,
                IFNULL(SUM(st.qator_summa), 0) as jami_tushum,
                IFNULL(AVG(st.birlik_narx), 0) as ortacha_sotish_narxi,
                MAX(s.sotilgan_vaqt) as oxirgi_sotilgan
            FROM mahsulotlar m
            LEFT JOIN savdo_tarkibi st ON m.id = st.mahsulot_id
            LEFT JOIN savdolar s ON st.savdo_id = s.id AND s.holat = 'YAKUNLANGAN'
            WHERE m.id = ?
            GROUP BY m.id
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Ombor harakati tarixi
    public function getStockHistory($id, $limit = 50) {
        $stmt = $this->db->prepare("
            SELECT 
                oj.*,
                f.fio as foydalanuvchi_fio
            FROM ombor_jurnali oj
            LEFT JOIN foydalanuvchilar f ON oj.foydalanuvchi_id = f.id
            WHERE oj.mahsulot_id = ?
            ORDER BY oj.yaratilgan_vaqt DESC
            LIMIT ?
        ");
        $stmt->execute([$id, $limit]);
        return $stmt->fetchAll();
    }
    
    // Eng ko'p sotilgan mahsulotlar
    public function getTopSelling($limit = 10, $startDate = null, $endDate = null) {
        $sql = "
            SELECT 
                m.id,
                m.nomi,
                m.shtrix_kod,
                COUNT(DISTINCT s.id) as savdolar_soni,
                SUM(st.soni) as jami_soni,
                SUM(st.qator_summa) as jami_summa,
                AVG(st.birlik_narx) as ortacha_narx
            FROM mahsulotlar m
            JOIN savdo_tarkibi st ON m.id = st.mahsulot_id
            JOIN savdolar s ON st.savdo_id = s.id
            WHERE s.holat = 'YAKUNLANGAN'
        ";
        
        $params = [];
        
        if ($startDate) {
            $sql .= " AND s.sotilgan_vaqt >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND s.sotilgan_vaqt <= ?";
            $params[] = $endDate;
        }
        
        $sql .= " GROUP BY m.id ORDER BY jami_soni DESC LIMIT ?";
        $params[] = $limit;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
}