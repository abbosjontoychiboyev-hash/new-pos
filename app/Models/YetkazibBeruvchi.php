<?php
namespace App\Models;

class YetkazibBeruvchi extends Model {
    protected $table = 'yetkazib_beruvchilar';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nomi', 'telefon', 'manzil', 'izoh', 'kelish_kuni',
        'qarz', 'oxirgi_olingan_sana', 'eslatma',
        'tolash_muddati', 'tolash_eslatma', 'oxirgi_tolov_sana',
        'jami_olingan', 'jami_tolangan'
    ];

    /**
     * Yetkazib beruvchining barcha kirimlarini olish
     */
    public function getKirimlar($supplierId, $limit = 20) {
        $limit = intval($limit); // LIMIT ni integer ga aylantiramiz
        $stmt = $this->db->prepare("
            SELECT k.*, u.fio as kiritgan_fio,
                   (SELECT COUNT(*) FROM kirim_tarkibi WHERE kirim_id = k.id) as mahsulotlar_soni
            FROM kirimlar k
            LEFT JOIN foydalanuvchilar u ON k.kiritgan_id = u.id
            WHERE k.yetkazib_beruvchi_id = ?
            ORDER BY k.kirim_vaqt DESC
            LIMIT {$limit}
        ");
        $stmt->execute([$supplierId]);
        return $stmt->fetchAll();
    }

    /**
     * Kirimga tegishli mahsulotlarni olish
     */
    public function getKirimProducts($kirimId) {
        $stmt = $this->db->prepare("
            SELECT kt.*, m.nomi, m.shtrix_kod, m.birlik
            FROM kirim_tarkibi kt
            LEFT JOIN mahsulotlar m ON kt.mahsulot_id = m.id
            WHERE kt.kirim_id = ?
            ORDER BY kt.id ASC
        ");
        $stmt->execute([$kirimId]);
        return $stmt->fetchAll();
    }

    /**
     * Dillerning to‘lovlar tarixi
     */
    public function getPayments($supplierId, $limit = 20) {
        $limit = intval($limit);
        $stmt = $this->db->prepare("
            SELECT t.*, u.fio as qabul_qilgan_fio
            FROM yetkazib_beruvchi_tolovlari t
            LEFT JOIN foydalanuvchilar u ON t.qabul_qilgan_id = u.id
            WHERE t.yetkazib_beruvchi_id = ?
            ORDER BY t.sana DESC
            LIMIT {$limit}
        ");
        $stmt->execute([$supplierId]);
        return $stmt->fetchAll();
    }

    /**
     * Oxirgi kirim ma'lumotlari
     */
    public function getLastKirim($supplierId) {
        $stmt = $this->db->prepare("
            SELECT k.*,
                   (SELECT SUM(qator_summa) FROM kirim_tarkibi WHERE kirim_id = k.id) as jami_summa
            FROM kirimlar k
            WHERE k.yetkazib_beruvchi_id = ?
            ORDER BY k.kirim_vaqt DESC
            LIMIT 1
        ");
        $stmt->execute([$supplierId]);
        return $stmt->fetch();
    }

    /**
     * Yangi to‘lov qo‘shish
     */
    public function addPayment($data, $userId) {
        $this->db->beginTransaction();
        try {
            // To‘lovni yozish
            $stmt = $this->db->prepare("
                INSERT INTO yetkazib_beruvchi_tolovlari
                (yetkazib_beruvchi_id, sana, summa, usul, izoh, qabul_qilgan_id)
                VALUES (?, NOW(), ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['yetkazib_beruvchi_id'],
                $data['summa'],
                $data['usul'],
                $data['izoh'] ?? null,
                $userId
            ]);

            // Diller qarzini kamaytirish va jami to‘langan summani oshirish
            $stmt = $this->db->prepare("
                UPDATE yetkazib_beruvchilar
                SET qarz = qarz - ?,
                    jami_tolangan = jami_tolangan + ?,
                    oxirgi_tolov_sana = CURDATE()
                WHERE id = ?
            ");
            $stmt->execute([$data['summa'], $data['summa'], $data['yetkazib_beruvchi_id']]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Add supplier payment error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Qarzdor dillerlar ro‘yxati (qarzi > 0)
     */
    public function getDebtors() {
        // 'ochirilgan_vaqt' ustuni mavjudligini tekshirish
        try {
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'ochirilgan_vaqt'");
            $stmt->execute();
            $hasSoftDelete = $stmt->fetch() ? true : false;
            
            if ($hasSoftDelete) {
                $sql = "SELECT * FROM {$this->table} WHERE qarz > 0 AND ochirilgan_vaqt IS NULL ORDER BY qarz DESC";
            } else {
                $sql = "SELECT * FROM {$this->table} WHERE qarz > 0 ORDER BY qarz DESC";
            }
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            // Xatolik bo‘lsa, oddiy so‘rov
            $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE qarz > 0 ORDER BY qarz DESC");
            return $stmt->fetchAll();
        }
    }

    /**
     * Bugun to‘lanishi kerak bo‘lgan qarzlar (murakkab, hozircha oddiy)
     */
    public function getDuePayments() {
        // Bu funksiyani keyinroq ishlab chiqamiz
        return [];
    }
}