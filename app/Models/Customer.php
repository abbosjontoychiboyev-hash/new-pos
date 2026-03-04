<?php
// app/Models/Customer.php

namespace App\Models;

class Customer extends Model {
    protected $table = 'mijozlar';
    protected $primaryKey = 'id';
    protected $fillable = ['fio', 'telefon', 'manzil', 'izoh', 'faol'];
    
    /**
     * Mijozning qarzini olish
     */
    public function getDebt($customerId = null) {
        $id = $customerId ?: $this->id;
        
        $stmt = $this->db->prepare("
            SELECT SUM(qarz_summa) as jami_qarz 
            FROM savdolar 
            WHERE mijoz_id = ? AND tolov_holati IN ('NASIYA', 'QISMAN') AND qarz_summa > 0
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        
        return $result['jami_qarz'] ?? 0;
    }
    
    /**
     * Mijozning to'lovlar tarixi
     */
    public function getPayments($customerId = null, $limit = 20) {
        $id = $customerId ?: $this->id;
        
        $stmt = $this->db->prepare("
            SELECT t.*, s.chek_raqami, u.fio as qabul_qilgan_fio
            FROM tolovlar t
            LEFT JOIN savdolar s ON t.savdo_id = s.id
            LEFT JOIN foydalanuvchilar u ON t.qabul_qilgan_id = u.id
            WHERE t.mijoz_id = ?
            ORDER BY t.tolangan_vaqt DESC
            LIMIT ?
        ");
        $stmt->execute([$id, $limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Mijozning savdo tarixi
     */
    public function getSales($customerId = null, $limit = 20) {
        $id = $customerId ?: $this->id;
        
        $stmt = $this->db->prepare("
            SELECT s.*, u.fio as kassir_fio
            FROM savdolar s
            LEFT JOIN foydalanuvchilar u ON s.kassir_id = u.id
            WHERE s.mijoz_id = ?
            ORDER BY s.sotilgan_vaqt DESC
            LIMIT ?
        ");
        $stmt->execute([$id, $limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Qarzdor mijozlar ro'yxati
     */
    public function getDebtors() {
        $stmt = $this->db->query("
            SELECT 
                m.*,
                SUM(s.qarz_summa) as jami_qarz,
                COUNT(s.id) as qarzli_savdolar,
                MAX(s.sotilgan_vaqt) as oxirgi_savdo
            FROM mijozlar m
            LEFT JOIN savdolar s ON m.id = s.mijoz_id AND s.tolov_holati IN ('NASIYA', 'QISMAN') AND s.qarz_summa > 0
            WHERE m.faol = 1 AND m.ochirilgan_vaqt IS NULL
            GROUP BY m.id
            HAVING jami_qarz > 0
            ORDER BY jami_qarz DESC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Mijoz qidirish (telefon yoki fio bo'yicha)
     */
    public function search($keyword, $limit = 20) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE ochirilgan_vaqt IS NULL
            AND (fio LIKE ? OR telefon LIKE ?)
            ORDER BY fio ASC
            LIMIT ?
        ");
        $searchTerm = "%{$keyword}%";
        $stmt->execute([$searchTerm, $searchTerm, $limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Mijoz statistikasi
     */
    public function getStats($customerId = null) {
        $id = $customerId ?: $this->id;
        
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(DISTINCT s.id) as jami_savdolar,
                IFNULL(SUM(s.yakuniy_summa), 0) as jami_xarid,
                IFNULL(SUM(s.qarz_summa), 0) as joriy_qarz,
                MAX(s.sotilgan_vaqt) as oxirgi_savdo,
                COUNT(DISTINCT CASE WHEN s.tolov_holati IN ('NASIYA', 'QISMAN') THEN s.id END) as qarzli_savdolar
            FROM mijozlar m
            LEFT JOIN savdolar s ON m.id = s.mijoz_id
            WHERE m.id = ? AND m.ochirilgan_vaqt IS NULL
            GROUP BY m.id
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}