<?php
// app/Models/Debt.php

namespace App\Models;

class Debt extends Model {
    protected $table = 'tolovlar';
    protected $primaryKey = 'id';
    
    /**
     * Mijozning joriy qarzini olish
     */
    public function getCustomerDebt($customerId) {
        $stmt = $this->db->prepare("
            SELECT 
                SUM(s.qarz_summa) as jami_qarz,
                COUNT(s.id) as qarzli_savdolar,
                MAX(s.sotilgan_vaqt) as oxirgi_savdo
            FROM savdolar s
            WHERE s.mijoz_id = ? 
            AND s.tolov_holati IN ('NASIYA', 'QISMAN') 
            AND s.qarz_summa > 0
        ");
        $stmt->execute([$customerId]);
        return $stmt->fetch();
    }
    
    /**
     * Mijozning barcha qarzlari tarixi
     */
    public function getCustomerDebtHistory($customerId) {
        $stmt = $this->db->prepare("
            SELECT 
                s.id as savdo_id,
                s.chek_raqami,
                s.sotilgan_vaqt,
                s.umumiy_summa,
                s.tolangan_summa as boshlangich_tolov,
                s.qarz_summa as qolgan_qarz,
                s.tolov_holati,
                s.izoh as savdo_izoh
            FROM savdolar s
            WHERE s.mijoz_id = ? 
            AND s.tolov_holati IN ('NASIYA', 'QISMAN')
            ORDER BY s.sotilgan_vaqt DESC
        ");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Mijozning to'lovlar tarixi
     */
    public function getCustomerPayments($customerId) {
        $stmt = $this->db->prepare("
            SELECT 
                t.*,
                s.chek_raqami,
                u.fio as qabul_qilgan_fio
            FROM tolovlar t
            LEFT JOIN savdolar s ON t.savdo_id = s.id
            LEFT JOIN foydalanuvchilar u ON t.qabul_qilgan_id = u.id
            WHERE t.mijoz_id = ?
            ORDER BY t.tolangan_vaqt DESC
        ");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Yangi to'lov qabul qilish
     */
    public function addPayment($data, $userId) {
        $this->db->beginTransaction();
        
        try {
            // 1. To'lovni tolovlar jadvaliga yozish
            $stmt = $this->db->prepare("
                INSERT INTO tolovlar (
                    savdo_id, mijoz_id, usul, summa, izoh, qabul_qilgan_id, tolangan_vaqt
                ) VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $data['savdo_id'],
                $data['mijoz_id'],
                $data['usul'],
                $data['summa'],
                $data['izoh'] ?? null,
                $userId
            ]);
            
            // 2. Eski qarzni olish
            $stmt = $this->db->prepare("SELECT qarz_summa FROM savdolar WHERE id = ?");
            $stmt->execute([$data['savdo_id']]);
            $eskiQarz = $stmt->fetch()['qarz_summa'];
            
            // 3. Yangi qarzni hisoblash
            $yangiQarz = $eskiQarz - $data['summa'];
            $yangiHolat = ($yangiQarz <= 0) ? 'TOLANGAN' : 'QISMAN';
            
            // 4. Manfiy bo'lib qolmasligi uchun tekshirish
            if ($yangiQarz < 0) {
                $yangiQarz = 0;
            }
            
            // 5. Savdoni yangilash
            $stmt = $this->db->prepare("
                UPDATE savdolar 
                SET 
                    tolangan_summa = tolangan_summa + ?,
                    qarz_summa = ?,
                    tolov_holati = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $data['summa'],
                $yangiQarz,
                $yangiHolat,
                $data['savdo_id']
            ]);
            
            $this->db->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Payment error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Barcha qarzdorlar ro'yxati
     */
    public function getAllDebtors() {
        $stmt = $this->db->query("
            SELECT 
                m.id,
                m.fio,
                m.telefon,
                m.manzil,
                SUM(s.qarz_summa) as jami_qarz,
                COUNT(s.id) as qarzli_savdolar,
                MAX(s.sotilgan_vaqt) as oxirgi_savdo
            FROM mijozlar m
            JOIN savdolar s ON m.id = s.mijoz_id
            WHERE s.tolov_holati IN ('NASIYA', 'QISMAN') 
            AND s.qarz_summa > 0
            GROUP BY m.id
            ORDER BY jami_qarz DESC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Muddati o'tgan qarzlar (30 kundan ko'p)
     */
    public function getOverdueDebts() {
        $stmt = $this->db->query("
            SELECT 
                m.id,
                m.fio,
                m.telefon,
                s.chek_raqami,
                s.qarz_summa,
                s.sotilgan_vaqt,
                DATEDIFF(NOW(), s.sotilgan_vaqt) as kechikkan_kun
            FROM savdolar s
            JOIN mijozlar m ON s.mijoz_id = m.id
            WHERE s.tolov_holati IN ('NASIYA', 'QISMAN') 
            AND s.qarz_summa > 0
            AND s.sotilgan_vaqt < DATE_SUB(NOW(), INTERVAL 30 DAY)
            ORDER BY s.sotilgan_vaqt ASC
        ");
        return $stmt->fetchAll();
    }
}