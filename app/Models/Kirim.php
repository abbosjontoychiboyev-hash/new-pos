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

    /**
     * Kirim yaratish
     */
    public function createKirim($data) {
        $data['kirim_vaqt'] = $data['kirim_vaqt'] ?? date('Y-m-d H:i:s');
        return $this->create($data);
    }

    /**
     * Kirimni yangilash
     */
    public function updateKirim($id, $data) {
        return $this->update($id, $data);
    }

    /**
     * Kirimni o'chirish
     */
    public function deleteKirim($id) {
        // Kirim tarkibini ham o'chirish
        $stmt = $this->db->prepare("DELETE FROM kirim_tarkibi WHERE kirim_id = ?");
        $stmt->execute([$id]);

        return $this->delete($id);
    }

    /**
     * Kirim tafsilotlari (tarkib bilan)
     */
    public function getDetails($id) {
        $kirim = $this->find($id);
        if (!$kirim) return null;

        // Kirim tarkibini olish
        $stmt = $this->db->prepare("
            SELECT kt.*, m.nomi as mahsulot_nomi, m.birlik
            FROM kirim_tarkibi kt
            LEFT JOIN mahsulotlar m ON kt.mahsulot_id = m.id
            WHERE kt.kirim_id = ?
            ORDER BY kt.id ASC
        ");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll();

        $kirim['items'] = $items;
        return $kirim;
    }

    /**
     * Kirimlar statistikasi
     */
    public function getStats($supplierId = null) {
        $where = $supplierId ? "WHERE yetkazib_beruvchi_id = ?" : "";
        $params = $supplierId ? [$supplierId] : [];

        $stmt = $this->db->prepare("
            SELECT
                COUNT(*) as total_kirimlar,
                SUM(umumiy_summa) as total_summa,
                AVG(umumiy_summa) as average_summa,
                MAX(kirim_vaqt) as last_kirim
            FROM {$this->table}
            {$where}
        ");
        $stmt->execute($params);
        return $stmt->fetch();
    }
}