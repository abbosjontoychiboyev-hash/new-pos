<?php
// app/Models/WarehouseJournal.php

namespace App\Models;

class WarehouseJournal extends Model {
    protected $table = 'ombor_jurnali';
    protected $primaryKey = 'id';
    protected $fillable = [
        'mahsulot_id', 'amal', 'miqdor_ozgarish', 'eski_miqdor', 
        'yangi_miqdor', 'manba_turi', 'manba_id', 'foydalanuvchi_id', 'izoh'
    ];
    
    // Mahsulot bo'yicha jurnal
    public function getByProduct($productId, $limit = 100) {
        $stmt = $this->db->prepare("
            SELECT oj.*, m.nomi as mahsulot_nomi, f.fio as foydalanuvchi_fio
            FROM {$this->table} oj
            LEFT JOIN mahsulotlar m ON oj.mahsulot_id = m.id
            LEFT JOIN foydalanuvchilar f ON oj.foydalanuvchi_id = f.id
            WHERE oj.mahsulot_id = ?
            ORDER BY oj.yaratilgan_vaqt DESC
            LIMIT ?
        ");
        $stmt->execute([$productId, $limit]);
        return $stmt->fetchAll();
    }
    
    // Sana oralig'idagi harakatlar
    public function getByDateRange($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT oj.*, m.nomi as mahsulot_nomi, f.fio as foydalanuvchi_fio
            FROM {$this->table} oj
            LEFT JOIN mahsulotlar m ON oj.mahsulot_id = m.id
            LEFT JOIN foydalanuvchilar f ON oj.foydalanuvchi_id = f.id
            WHERE DATE(oj.yaratilgan_vaqt) BETWEEN ? AND ?
            ORDER BY oj.yaratilgan_vaqt DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
    
    // Kunlik ombor hisoboti
    public function getDailyReport($date) {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(yaratilgan_vaqt) as sana,
                amal,
                manba_turi,
                COUNT(*) as harakatlar_soni,
                SUM(miqdor_ozgarish) as umumiy_ozgarish,
                COUNT(DISTINCT mahsulot_id) as mahsulotlar_soni
            FROM {$this->table}
            WHERE DATE(yaratilgan_vaqt) = ?
            GROUP BY amal, manba_turi
        ");
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }
}