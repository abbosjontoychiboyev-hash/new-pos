<?php
// app/Models/Pos.php

namespace App\Models;

class Pos extends Model {
    protected $table = 'savdolar';
    protected $primaryKey = 'id';
    
    /**
     * Yangi savdo chek raqami yaratish
     */
    public function generateChekRaqami() {
        $date = date('Ymd');
        $attempts = 0;
        $maxAttempts = 10;
        
        while ($attempts < $maxAttempts) {
            // Tasodifiy son qo'shamiz
            $random = rand(100, 999);
            
            // Jadvalda chek_raqami ustuni borligini tekshirish
            try {
                $stmt = $this->db->query("SHOW COLUMNS FROM savdolar LIKE 'chek_raqami'");
                $hasChekRaqami = $stmt->fetch() ? true : false;
                
                if ($hasChekRaqami) {
                    // Bugungi savdolar sonini hisoblash
                    $stmt = $this->db->prepare("
                        SELECT COUNT(*) as count 
                        FROM savdolar 
                        WHERE DATE(sotilgan_vaqt) = CURDATE() 
                        AND chek_raqami IS NOT NULL 
                        AND chek_raqami != ''
                    ");
                    $stmt->execute();
                    $count = $stmt->fetch()['count'] + 1;
                    
                    $chekRaqami = 'CHK-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT) . '-' . $random;
                } else {
                    // Agar chek_raqami ustuni bo'lmasa, ID dan foydalanamiz
                    $chekRaqami = 'CHK-' . $date . '-' . rand(1000, 9999) . '-' . $random;
                }
                
                // Bu raqam allaqachon ishlatilganmi?
                $checkStmt = $this->db->prepare("SELECT id FROM savdolar WHERE chek_raqami = ?");
                $checkStmt->execute([$chekRaqami]);
                
                if (!$checkStmt->fetch()) {
                    // Unique raqam topildi
                    return $chekRaqami;
                }
                
                $attempts++;
                
            } catch (\PDOException $e) {
                // Xatolik bo'lsa, oddiy raqam qaytaramiz
                return 'CHK-' . $date . '-' . rand(1000, 9999);
            }
        }
        
        // Agar unique raqam topilmasa, timestamp bilan
        return 'CHK-' . $date . '-' . time() . '-' . rand(100, 999);
    }
    
    /**
     * Savatdagi mahsulotlarni saqlash (mijoz majburiy emas)
     */
    public function saveSale($data, $items, $userId) {
        $this->db->beginTransaction();
        
        try {
            // Jadval strukturasini tekshirish
            $columns = $this->getTableColumns('savdolar');
            
            // Savdo ma'lumotlarini tayyorlash
            $saleData = [];
            $saleParams = [];
            
            // Chek raqami
            if (in_array('chek_raqami', $columns)) {
                    if (empty($data['chek_raqami'])) {
                        $data['chek_raqami'] = 'CHK-' . date('Ymd') . '-' . rand(1000, 9999) . '-' . time();
                    }
                    $saleData[] = 'chek_raqami';
                    $saleParams[] = $data['chek_raqami'];
                }
            
            // Kassir
            if (in_array('kassir_id', $columns)) {
                $saleData[] = 'kassir_id';
                $saleParams[] = $userId;
            }
            
            // Mijoz (ixtiyoriy)
            if (in_array('mijoz_id', $columns)) {
                $saleData[] = 'mijoz_id';
                $saleParams[] = $data['mijoz_id'] ?? null;
            }
            
            // To'lov usuli
            if (in_array('tolov_usuli', $columns)) {
                $saleData[] = 'tolov_usuli';
                $saleParams[] = $data['tolov_usuli'];
            }
            
            // To'lov holati
            if (in_array('tolov_holati', $columns)) {
                $saleData[] = 'tolov_holati';
                $saleParams[] = $data['tolov_holati'];
            }
            
            // Summalar
            if (in_array('umumiy_summa', $columns)) {
                $saleData[] = 'umumiy_summa';
                $saleParams[] = $data['umumiy_summa'];
            }
            
            if (in_array('chegirma_summa', $columns)) {
                $saleData[] = 'chegirma_summa';
                $saleParams[] = $data['chegirma_summa'];
            }
            
            if (in_array('yakuniy_summa', $columns)) {
                $saleData[] = 'yakuniy_summa';
                $saleParams[] = $data['yakuniy_summa'];
            }
            
            if (in_array('tolangan_summa', $columns)) {
                $saleData[] = 'tolangan_summa';
                $saleParams[] = $data['tolangan_summa'];
            }
            
            if (in_array('qarz_summa', $columns)) {
                $saleData[] = 'qarz_summa';
                $saleParams[] = $data['qarz_summa'];
            }
            
            if (in_array('holat', $columns)) {
                $saleData[] = 'holat';
                $saleParams[] = $data['holat'];
            }
            
            if (in_array('izoh', $columns)) {
                $saleData[] = 'izoh';
                $saleParams[] = $data['izoh'] ?? null;
            }
            
            if (in_array('sotilgan_vaqt', $columns)) {
                $saleData[] = 'sotilgan_vaqt';
                $saleParams[] = date('Y-m-d H:i:s');
            }
            
            // SQL so'rovni tuzish
            $fields = implode(', ', $saleData);
            $placeholders = implode(', ', array_fill(0, count($saleData), '?'));
            
            $sql = "INSERT INTO savdolar ({$fields}) VALUES ({$placeholders})";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($saleParams);
            
            $savdoId = $this->db->lastInsertId();
            
            // Savdo tarkibi uchun jadval strukturasini tekshirish
            $itemColumns = $this->getTableColumns('savdo_tarkibi');
            
            // Savdo tarkibini saqlash
            foreach ($items as $item) {
                $itemData = [];
                $itemParams = [];
                
                if (in_array('savdo_id', $itemColumns)) {
                    $itemData[] = 'savdo_id';
                    $itemParams[] = $savdoId;
                }
                
                if (in_array('mahsulot_id', $itemColumns)) {
                    $itemData[] = 'mahsulot_id';
                    $itemParams[] = $item['mahsulot_id'];
                }
                
                if (in_array('soni', $itemColumns)) {
                    $itemData[] = 'soni';
                    $itemParams[] = $item['soni'];
                }
                
                if (in_array('birlik_narx', $itemColumns)) {
                    $itemData[] = 'birlik_narx';
                    $itemParams[] = $item['birlik_narx'];
                }
                
                if (in_array('chegirma', $itemColumns)) {
                    $itemData[] = 'chegirma';
                    $itemParams[] = $item['chegirma'] ?? 0;
                }
                
                if (in_array('qator_summa', $itemColumns)) {
                    $itemData[] = 'qator_summa';
                    $itemParams[] = $item['qator_summa'];
                }
                
                $fields2 = implode(', ', $itemData);
                $placeholders2 = implode(', ', array_fill(0, count($itemData), '?'));
                
                $sql2 = "INSERT INTO savdo_tarkibi ({$fields2}) VALUES ({$placeholders2})";
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->execute($itemParams);
                
                // Mahsulot miqdorini kamaytirish
                $stockStmt = $this->db->prepare("UPDATE mahsulotlar SET miqdor = miqdor - ? WHERE id = ?");
                $stockStmt->execute([$item['soni'], $item['mahsulot_id']]);
            }
            
            // Agar nasiya bo'lsa va mijoz tanlangan bo'lsa, to'lovlar jadvaliga yozish
            if (($data['tolov_holati'] == 'QISMAN' || $data['tolov_holati'] == 'NASIYA') && !empty($data['mijoz_id'])) {
                $paymentColumns = $this->getTableColumns('tolovlar');
                
                if (!empty($paymentColumns)) {
                    $paymentData = [];
                    $paymentParams = [];
                    
                    if (in_array('savdo_id', $paymentColumns)) {
                        $paymentData[] = 'savdo_id';
                        $paymentParams[] = $savdoId;
                    }
                    
                    if (in_array('mijoz_id', $paymentColumns)) {
                        $paymentData[] = 'mijoz_id';
                        $paymentParams[] = $data['mijoz_id'];
                    }
                    
                    if (in_array('usul', $paymentColumns)) {
                        $paymentData[] = 'usul';
                        $paymentParams[] = $data['tolov_usuli'];
                    }
                    
                    if (in_array('summa', $paymentColumns)) {
                        $paymentData[] = 'summa';
                        $paymentParams[] = $data['tolangan_summa'];
                    }
                    
                    if (in_array('izoh', $paymentColumns)) {
                        $paymentData[] = 'izoh';
                        $paymentParams[] = 'Boshlang\'ich to\'lov';
                    }
                    
                    if (in_array('qabul_qilgan_id', $paymentColumns)) {
                        $paymentData[] = 'qabul_qilgan_id';
                        $paymentParams[] = $userId;
                    }
                    
                    if (!empty($paymentData)) {
                        $fields3 = implode(', ', $paymentData);
                        $placeholders3 = implode(', ', array_fill(0, count($paymentData), '?'));
                        $sql3 = "INSERT INTO tolovlar ({$fields3}) VALUES ({$placeholders3})";
                        $stmt3 = $this->db->prepare($sql3);
                        $stmt3->execute($paymentParams);
                    }
                }
            }
            
            $this->db->commit();
            return $savdoId;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("POS save error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Jadval ustunlarini olish
     */
    private function getTableColumns($table) {
        try {
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$table}");
            $stmt->execute();
            $columns = [];
            while ($row = $stmt->fetch()) {
                $columns[] = $row['Field'];
            }
            return $columns;
        } catch (\PDOException $e) {
            return [];
        }
    }
    
    /**
     * Joriy smenani olish
     */
    public function getCurrentSmena($userId) {
        $stmt = $this->db->prepare("
            SELECT * FROM kassa_smenalari 
            WHERE kassir_id = ? AND holat = 'OCHIQ' 
            ORDER BY id DESC LIMIT 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
    
    /**
     * Smena ochish
     */
    public function openSmena($userId, $naqd) {
        $stmt = $this->db->prepare("
            INSERT INTO kassa_smenalari (kassir_id, ochilgan_vaqt, ochilish_naqd, holat)
            VALUES (?, NOW(), ?, 'OCHIQ')
        ");
        return $stmt->execute([$userId, $naqd]);
    }
    
    /**
     * Smena yopish
     */
    public function closeSmena($smenaId, $naqd) {
        $stmt = $this->db->prepare("
            UPDATE kassa_smenalari 
            SET yopilgan_vaqt = NOW(), yopilish_naqd = ?, holat = 'YOPIQ'
            WHERE id = ?
        ");
        return $stmt->execute([$naqd, $smenaId]);
    }
}