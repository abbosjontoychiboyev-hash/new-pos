<?php
namespace App\Models;

class YetkazibBeruvchi extends Model {
    protected $table = 'yetkazib_beruvchilar';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nomi', 'telefon', 'manzil', 'qarz_summa', 
        'oxirgi_tolov_sana', 'kelish_kuni', 'izoh', 'faol'
    ];

    /**
     * Barcha faol yetkazib beruvchilar
     */
    public function getActive() {
        return $this->where(['faol' => 1], 'nomi ASC');
    }

    /**
     * Qarzdor yetkazib beruvchilar
     */
    public function getDebtors() {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE qarz_summa > 0 AND faol = 1
            ORDER BY qarz_summa DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Qarzni yangilash (kirim yoki to'lovdan keyin)
     */
    public function updateDebt($id, $changeAmount) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET qarz_summa = qarz_summa + ? 
            WHERE id = ?
        ");
        return $stmt->execute([$changeAmount, $id]);
    }

    /**
     * To'lov qilish
     */
    public function makePayment($id, $summa) {
        $this->db->beginTransaction();
        try {
            // Yetkazib beruvchi qarzini kamaytirish
            $stmt = $this->db->prepare("
                UPDATE {$this->table} 
                SET qarz_summa = qarz_summa - ?,
                    oxirgi_tolov_sana = CURDATE()
                WHERE id = ?
            ");
            $stmt->execute([$summa, $id]);

            // To'lov tarixiga yozish (ixtiyoriy)
            // (Agar to'lovlar jadvali mavjud bo'lsa)
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Bugun kelishi kerak bo'lgan yetkazib beruvchilar
     */
    public function getTodaysDeliveries() {
        $today = date('l'); // Monday, Tuesday, ...
        $todayUz = $this->translateDayToUzbek($today);
        
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE (kelish_kuni = ? OR kelish_kuni = 'Har kuni') 
            AND faol = 1
        ");
        $stmt->execute([$todayUz]);
        return $stmt->fetchAll();
    }

    private function translateDayToUzbek($day) {
        $days = [
            'Monday' => 'Dushanba',
            'Tuesday' => 'Seshanba',
            'Wednesday' => 'Chorshanba',
            'Thursday' => 'Payshanba',
            'Friday' => 'Juma',
            'Saturday' => 'Shanba',
            'Sunday' => 'Yakshanba'
        ];
        return $days[$day] ?? $day;
    }
}