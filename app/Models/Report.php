<?php
// app/Models/Report.php

namespace App\Models;

class Report extends Model {
    protected $table = 'savdolar';
    
    /**
     * Kunlik savdo hisoboti
     */
    public function getDailyReport($date = null) {
        $date = $date ?: date('Y-m-d');
        
        $stmt = $this->db->prepare("
            SELECT 
                DATE(s.sotilgan_vaqt) as sana,
                COUNT(DISTINCT s.id) as savdolar_soni,
                COUNT(DISTINCT s.kassir_id) as kassirlar_soni,
                COUNT(DISTINCT s.mijoz_id) as mijozlar_soni,
                SUM(s.umumiy_summa) as jami_savdo,
                SUM(s.chegirma_summa) as jami_chegirma,
                SUM(s.yakuniy_summa) as sof_tushum,
                SUM(s.tolangan_summa) as jami_tolov,
                SUM(s.qarz_summa) as jami_qarz,
                AVG(s.yakuniy_summa) as ortacha_chek
            FROM savdolar s
            WHERE DATE(s.sotilgan_vaqt) = ? AND s.holat = 'YAKUNLANGAN'
            GROUP BY DATE(s.sotilgan_vaqt)
        ");
        $stmt->execute([$date]);
        return $stmt->fetch();
    }
    
    /**
     * Kunlik savdolar ro'yxati
     */
    public function getDailySales($date = null) {
        $date = $date ?: date('Y-m-d');
        
        $stmt = $this->db->prepare("
            SELECT 
                s.id,
                s.chek_raqami,
                s.sotilgan_vaqt,
                s.yakuniy_summa,
                s.tolov_usuli,
                s.tolov_holati,
                u.fio as kassir_fio,
                m.fio as mijoz_fio,
                (SELECT COUNT(*) FROM savdo_tarkibi WHERE savdo_id = s.id) as mahsulotlar_soni
            FROM savdolar s
            LEFT JOIN foydalanuvchilar u ON s.kassir_id = u.id
            LEFT JOIN mijozlar m ON s.mijoz_id = m.id
            WHERE DATE(s.sotilgan_vaqt) = ? AND s.holat = 'YAKUNLANGAN'
            ORDER BY s.sotilgan_vaqt DESC
        ");
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }
    
    /**
     * Oylik savdo hisoboti
     */
    public function getMonthlyReport($year, $month) {
        $stmt = $this->db->prepare("
            SELECT 
                DAY(s.sotilgan_vaqt) as kun,
                COUNT(DISTINCT s.id) as savdolar_soni,
                SUM(s.yakuniy_summa) as kunlik_savdo,
                SUM(s.chegirma_summa) as kunlik_chegirma,
                SUM(s.qarz_summa) as kunlik_qarz
            FROM savdolar s
            WHERE YEAR(s.sotilgan_vaqt) = ? AND MONTH(s.sotilgan_vaqt) = ? AND s.holat = 'YAKUNLANGAN'
            GROUP BY DAY(s.sotilgan_vaqt)
            ORDER BY kun ASC
        ");
        $stmt->execute([$year, $month]);
        return $stmt->fetchAll();
    }
    
    /**
     * Yillik savdo hisoboti
     */
    public function getYearlyReport($year) {
        $stmt = $this->db->prepare("
            SELECT 
                MONTH(s.sotilgan_vaqt) as oy,
                COUNT(DISTINCT s.id) as savdolar_soni,
                SUM(s.yakuniy_summa) as oylik_savdo,
                SUM(s.chegirma_summa) as oylik_chegirma,
                AVG(s.yakuniy_summa) as ortacha_chek
            FROM savdolar s
            WHERE YEAR(s.sotilgan_vaqt) = ? AND s.holat = 'YAKUNLANGAN'
            GROUP BY MONTH(s.sotilgan_vaqt)
            ORDER BY oy ASC
        ");
        $stmt->execute([$year]);
        return $stmt->fetchAll();
    }
    
    /**
     * Top mahsulotlar hisoboti - TUZATILGAN
     */
    public function getTopProducts($startDate, $endDate, $limit = 10) {
        // LIMIT ni to'g'ridan-to'g'ri so'rovga qo'shamiz
        $limit = intval($limit);
        
        $sql = "
            SELECT 
                p.id,
                p.nomi,
                p.shtrix_kod,
                p.birlik,
                k.nomi as kategoriya,
                COUNT(DISTINCT s.id) as savdolar_soni,
                SUM(st.soni) as jami_soni,
                SUM(st.qator_summa) as jami_summa,
                AVG(st.birlik_narx) as ortacha_narx,
                SUM(st.soni * p.kelish_narxi) as jami_tannarx,
                SUM(st.qator_summa) - SUM(st.soni * p.kelish_narxi) as jami_foyda
            FROM mahsulotlar p
            JOIN savdo_tarkibi st ON p.id = st.mahsulot_id
            JOIN savdolar s ON st.savdo_id = s.id
            LEFT JOIN kategoriyalar k ON p.kategoriya_id = k.id
            WHERE DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN'
            GROUP BY p.id
            ORDER BY jami_soni DESC
            LIMIT {$limit}
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
    
    /**
     * Kassir hisoboti
     */
    public function getCashierReport($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                u.id,
                u.fio,
                COUNT(DISTINCT s.id) as savdolar_soni,
                SUM(IFNULL(s.yakuniy_summa, 0)) as jami_savdo,
                AVG(IFNULL(s.yakuniy_summa, 0)) as ortacha_chek,
                SUM(IFNULL(s.chegirma_summa, 0)) as jami_chegirma,
                COUNT(DISTINCT CASE WHEN s.tolov_holati = 'NASIYA' THEN s.id END) as nasiya_soni,
                SUM(CASE WHEN s.tolov_holati IN ('NASIYA', 'QISMAN') THEN IFNULL(s.qarz_summa, 0) ELSE 0 END) as jami_qarz
            FROM foydalanuvchilar u
            LEFT JOIN savdolar s ON u.id = s.kassir_id AND DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN'
            WHERE u.rol_id IN (1, 2) -- Admin va Kassir
            GROUP BY u.id
            ORDER BY jami_savdo DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
    
    /**
     * Foyda hisoboti
     */
    public function getProfitReport($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(s.sotilgan_vaqt) as sana,
                COUNT(DISTINCT s.id) as savdolar_soni,
                SUM(s.yakuniy_summa) as tushum,
                SUM(st.soni * p.kelish_narxi) as tannarx,
                SUM(s.yakuniy_summa) - SUM(st.soni * p.kelish_narxi) as yalpi_foyda,
                CASE 
                    WHEN SUM(s.yakuniy_summa) > 0 
                    THEN (SUM(s.yakuniy_summa) - SUM(st.soni * p.kelish_narxi)) / SUM(s.yakuniy_summa) * 100 
                    ELSE 0 
                END as foyda_foizi
            FROM savdolar s
            JOIN savdo_tarkibi st ON s.id = st.savdo_id
            JOIN mahsulotlar p ON st.mahsulot_id = p.id
            WHERE DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN'
            GROUP BY DATE(s.sotilgan_vaqt)
            ORDER BY sana DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
    
    /**
     * Qarzdorlar hisoboti
     */
    public function getDebtReport() {
        $stmt = $this->db->prepare("
            SELECT 
                m.id,
                m.fio,
                m.telefon,
                COUNT(DISTINCT s.id) as qarzli_savdolar,
                SUM(IFNULL(s.qarz_summa, 0)) as jami_qarz,
                MAX(s.sotilgan_vaqt) as oxirgi_savdo,
                DATEDIFF(NOW(), MAX(s.sotilgan_vaqt)) as kechikkan_kun
            FROM mijozlar m
            JOIN savdolar s ON m.id = s.mijoz_id
            WHERE s.tolov_holati IN ('NASIYA', 'QISMAN') AND s.qarz_summa > 0
            GROUP BY m.id
            ORDER BY jami_qarz DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * To'lov usullari bo'yicha hisobot
     */
    public function getPaymentMethodReport($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                s.tolov_usuli,
                COUNT(*) as savdolar_soni,
                SUM(s.yakuniy_summa) as jami_summa,
                AVG(s.yakuniy_summa) as ortacha_summa
            FROM savdolar s
            WHERE DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN'
            GROUP BY s.tolov_usuli
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
    
    /**
     * Kategoriyalar bo'yicha hisobot
     */
    public function getCategoryReport($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                k.id,
                k.nomi,
                COUNT(DISTINCT s.id) as savdolar_soni,
                COUNT(DISTINCT st.mahsulot_id) as mahsulotlar_soni,
                SUM(IFNULL(st.soni, 0)) as jami_soni,
                SUM(IFNULL(st.qator_summa, 0)) as jami_summa
            FROM kategoriyalar k
            LEFT JOIN mahsulotlar p ON k.id = p.kategoriya_id
            LEFT JOIN savdo_tarkibi st ON p.id = st.mahsulot_id
            LEFT JOIN savdolar s ON st.savdo_id = s.id AND DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN'
            GROUP BY k.id
            ORDER BY jami_summa DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
    
    /**
     * Soatlik savdo hisoboti (qaysi soatlarda ko'p savdo)
     */
    public function getHourlyReport($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                HOUR(s.sotilgan_vaqt) as soat,
                COUNT(*) as savdolar_soni,
                SUM(s.yakuniy_summa) as jami_summa
            FROM savdolar s
            WHERE DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN'
            GROUP BY HOUR(s.sotilgan_vaqt)
            ORDER BY soat ASC
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
    
    /**
     * Hafta kunlari bo'yicha hisobot
     */
    public function getWeekdayReport($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                DAYOFWEEK(s.sotilgan_vaqt) as kun_raqami,
                CASE DAYOFWEEK(s.sotilgan_vaqt)
                    WHEN 1 THEN 'Yakshanba'
                    WHEN 2 THEN 'Dushanba'
                    WHEN 3 THEN 'Seshanba'
                    WHEN 4 THEN 'Chorshanba'
                    WHEN 5 THEN 'Payshanba'
                    WHEN 6 THEN 'Juma'
                    WHEN 7 THEN 'Shanba'
                END as kun_nomi,
                COUNT(*) as savdolar_soni,
                SUM(s.yakuniy_summa) as jami_summa
            FROM savdolar s
            WHERE DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN'
            GROUP BY DAYOFWEEK(s.sotilgan_vaqt)
            ORDER BY kun_raqami ASC
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
    
    /**
     * Mijozlar statistikasi
     */
    public function getCustomerStats($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(DISTINCT s.mijoz_id) as jami_mijozlar,
                COUNT(DISTINCT CASE WHEN s.mijoz_id IS NOT NULL THEN s.id END) as mijozli_savdolar,
                COUNT(DISTINCT CASE WHEN s.mijoz_id IS NULL THEN s.id END) as anonim_savdolar,
                AVG(s.yakuniy_summa) as mijoz_ortacha_savdo
            FROM savdolar s
            WHERE DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN'
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetch();
    }
    
    /**
     * Dashboard uchun asosiy statistikalar
     */
    public function getDashboardStats() {
        $today = date('Y-m-d');
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        
        $stats = [];
        
        // Bugungi statistika
        $todayStats = $this->getDailyReport($today);
        $stats['today'] = $todayStats ?: [
            'savdolar_soni' => 0,
            'jami_savdo' => 0,
            'jami_qarz' => 0
        ];
        
        // Oylik statistika
        $monthlySales = $this->getMonthlyReport(date('Y'), date('m'));
        $monthlyTotal = 0;
        $monthlyCount = 0;
        foreach ($monthlySales as $day) {
            $monthlyTotal += $day['kunlik_savdo'];
            $monthlyCount += $day['savdolar_soni'];
        }
        $stats['monthly'] = [
            'jami_savdo' => $monthlyTotal,
            'savdolar_soni' => $monthlyCount,
            'ortacha_kunlik' => count($monthlySales) > 0 ? $monthlyTotal / count($monthlySales) : 0
        ];
        
        // Kam qolgan mahsulotlar
        $productModel = new Product();
        $stats['lowStock'] = count($productModel->lowStock());
        
        // Jami mahsulotlar
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM mahsulotlar WHERE ochirilgan_vaqt IS NULL");
        $stats['totalProducts'] = $stmt->fetch()['count'];
        
        // Jami mijozlar
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM mijozlar WHERE ochirilgan_vaqt IS NULL");
        $stats['totalCustomers'] = $stmt->fetch()['count'];
        
        // Qarzdorlar
        $debtors = $this->getDebtReport();
        $stats['totalDebt'] = 0;
        foreach ($debtors as $debtor) {
            $stats['totalDebt'] += $debtor['jami_qarz'];
        }
        $stats['debtorCount'] = count($debtors);
        
        return $stats;
    }
    
    /**
     * Excel export uchun ma'lumotlarni tayyorlash
     */
    public function getExportData($type, $startDate, $endDate) {
        switch ($type) {
            case 'sales':
                return $this->getDailySalesRange($startDate, $endDate);
            case 'profit':
                return $this->getProfitReport($startDate, $endDate);
            case 'products':
                return $this->getTopProducts($startDate, $endDate, 1000);
            case 'debt':
                return $this->getDebtReport();
            default:
                return [];
        }
    }
    
    /**
     * Sana oralig'idagi kunlik savdolar
     */
    public function getDailySalesRange($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(s.sotilgan_vaqt) as sana,
                COUNT(DISTINCT s.id) as savdolar_soni,
                SUM(s.yakuniy_summa) as jami_savdo,
                SUM(s.chegirma_summa) as jami_chegirma,
                SUM(s.qarz_summa) as jami_qarz
            FROM savdolar s
            WHERE DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN'
            GROUP BY DATE(s.sotilgan_vaqt)
            ORDER BY sana DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
}