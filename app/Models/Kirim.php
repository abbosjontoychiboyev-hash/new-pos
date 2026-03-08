<?php
namespace App\Models;

class Kirim extends Model {
    protected $table = 'kirimlar';
    protected $primaryKey = 'id';
    protected $fillable = [
        'yetkazib_beruvchi_id', 'kiritgan_id', 'umumiy_summa', 
        'holat', 'izoh', 'kirim_vaqt'
    ];
    
    /**
     * Yetkazib beruvchi bo'yicha kirimlar
     */
    public function getBySupplier($supplierId, $limit = 50) {
        $stmt = $this->db->prepare("
            SELECT k.*, u.fio as kiritgan_fio
            FROM {$this->table} k
            LEFT JOIN foydalanuvchilar u ON k.kiritgan_id = u.id
            WHERE k.yetkazib_beruvchi_id = ?
            ORDER BY k.kirim_vaqt DESC
            LIMIT ?
        ");
        $stmt->execute([$supplierId, $limit]);
        return $stmt->fetchAll();
    }
}