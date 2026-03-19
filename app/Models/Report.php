<?php
// app/Models/Report.php

namespace App\Models;

class Report extends Model {
    protected $table = 'savdolar';
    
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
     * Dillerlar / yetkazib beruvchilar hisobot
     */
    public function getDealerReport() {
        $stmt = $this->db->prepare("
            SELECT 
                id,
                nomi,
                telefon,
                qarz,
                jami_olingan,
                jami_tolangan,
                oxirgi_olingan_sana,
                oxirgi_tolov_sana
            FROM yetkazib_beruvchilar
            WHERE ochirilgan_vaqt IS NULL
            ORDER BY qarz DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Smеna hisobot
     */
    public function getShiftReport($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                k.*,
                u.fio as kassir_fio,
                (SELECT IFNULL(SUM(tolangan_summa), 0) FROM savdolar WHERE kassir_id = k.kassir_id AND DATE(sotilgan_vaqt) BETWEEN ? AND ? AND holat = 'YAKUNLANGAN' AND tolov_usuli = 'NAQD') as jami_naqd_tolov,
                (SELECT IFNULL(SUM(summa), 0) FROM qaytarishlar q JOIN savdolar s ON q.savdo_id = s.id WHERE s.kassir_id = k.kassir_id AND DATE(q.qaytarilgan_vaqt) BETWEEN ? AND ?) as qaytarilgan_summa,
                (SELECT IFNULL(SUM(summa), 0) FROM yetkazib_beruvchi_tolovlari WHERE kiritgan_id = k.kassir_id AND DATE(sana) BETWEEN ? AND ?) as diller_tolovlari
            FROM kassa_smenalari k
            LEFT JOIN foydalanuvchilar u ON u.id = k.kassir_id
            WHERE DATE(k.ochilgan_vaqt) BETWEEN ? AND ?
            ORDER BY k.ochilgan_vaqt DESC
        ");
        $stmt->execute([$startDate, $endDate, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate]);
        $results = $stmt->fetchAll();

        // Calculate expected cash per shift
        foreach ($results as &$row) {
            $row['expected_cash'] = ($row['ochilish_naqd'] ?? 0) + ($row['jami_naqd_tolov'] ?? 0) - ($row['qaytarilgan_summa'] ?? 0) - ($row['diller_tolovlari'] ?? 0);
        }
        unset($row);

        return $results;
    }

    /**
     * Qaytarishlar hisobot
     */
    public function getReturnReport($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                r.*, 
                s.chek_raqami,
                u.fio as kassir_fio,
                m.fio as mijoz_fio,
                p.nomi as mahsulot_nomi
            FROM qaytarishlar r
            LEFT JOIN savdolar s ON s.id = r.savdo_id
            LEFT JOIN foydalanuvchilar u ON u.id = r.foydalanuvchi_id
            LEFT JOIN mijozlar m ON m.id = s.mijoz_id
            LEFT JOIN mahsulotlar p ON p.id = r.mahsulot_id
            WHERE DATE(r.qaytarilgan_vaqt) BETWEEN ? AND ?
            ORDER BY r.qaytarilgan_vaqt DESC
        ");
        $stmt->execute([$startDate, $endDate]);
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
        $monthlySales = $this->getMonthlyDailyReport(date('Y'), date('m'));
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
    public function getDailySalesRange($startDate, $endDate, $filters = []) {
        $whereConditions = [];
        $params = [$startDate, $endDate];

        if (!empty($filters['cashier_id'])) {
            $whereConditions[] = "s.kassir_id = ?";
            $params[] = $filters['cashier_id'];
        }

        $whereClause = !empty($whereConditions) ? " AND " . implode(" AND ", $whereConditions) : "";

        $stmt = $this->db->prepare("
            SELECT 
                DATE(s.sotilgan_vaqt) as sana,
                COUNT(DISTINCT s.id) as savdolar_soni,
                SUM(s.yakuniy_summa) as jami_savdo,
                SUM(s.chegirma_summa) as jami_chegirma,
                SUM(s.qarz_summa) as jami_qarz
            FROM savdolar s
            WHERE DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN' {$whereClause}
            GROUP BY DATE(s.sotilgan_vaqt)
            ORDER BY sana DESC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Kunlik hisobot - to'liq ma'lumotlar
     */
    public function getDailyReport($date, $filters = []) {
        $date = $date ?: date('Y-m-d');

        // Asosiy savdo ma'lumotlari
        $salesData = $this->getSalesSummary($date, $date, $filters);

        // Diller to'lovlari
        $dealerPayments = $this->getDealerPayments($date, $date, $filters);

        // Qarz to'lovlari
        $debtCollections = $this->getDebtCollections($date, $date, $filters);

        // Qaytarishlar
        $returns = $this->getReturnsSummary($date, $date, $filters);

        // Kassa ma'lumotlari
        $cashData = $this->getCashSummary($date, $date, $filters);

        return [
            'date' => $date,
            'sales' => $salesData,
            'dealer_payments' => $dealerPayments,
            'debt_collections' => $debtCollections,
            'returns' => $returns,
            'cash' => $cashData
        ];
    }

    /**
     * Haftalik hisobot
     */
    public function getWeeklyReport($startDate, $endDate, $filters = []) {
        // Asosiy savdo ma'lumotlari
        $salesData = $this->getSalesSummary($startDate, $endDate, $filters);

        // Diller to'lovlari
        $dealerPayments = $this->getDealerPayments($startDate, $endDate, $filters);

        // Qarz to'lovlari
        $debtCollections = $this->getDebtCollections($startDate, $endDate, $filters);

        // Qaytarishlar
        $returns = $this->getReturnsSummary($startDate, $endDate, $filters);

        // Kassa ma'lumotlari
        $cashData = $this->getCashSummary($startDate, $endDate, $filters);

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'sales' => $salesData,
            'dealer_payments' => $dealerPayments,
            'debt_collections' => $debtCollections,
            'returns' => $returns,
            'cash' => $cashData
        ];
    }

    /**
     * Oylik hisobot
     */
    public function getMonthlyReport($year, $month, $filters = []) {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        // Asosiy savdo ma'lumotlari
        $salesData = $this->getSalesSummary($startDate, $endDate, $filters);

        // Diller to'lovlari
        $dealerPayments = $this->getDealerPayments($startDate, $endDate, $filters);

        // Qarz to'lovlari
        $debtCollections = $this->getDebtCollections($startDate, $endDate, $filters);

        // Qaytarishlar
        $returns = $this->getReturnsSummary($startDate, $endDate, $filters);

        // Kassa ma'lumotlari
        $cashData = $this->getCashSummary($startDate, $endDate, $filters);

        return [
            'year' => $year,
            'month' => $month,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'sales' => $salesData,
            'dealer_payments' => $dealerPayments,
            'debt_collections' => $debtCollections,
            'returns' => $returns,
            'cash' => $cashData
        ];
    }

    /**
     * Kunlik oylik hisobot (kun bo'yicha)
     */
    public function getMonthlyDailyReport($year, $month, $filters = []) {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $rows = $this->getDailySalesRange($startDate, $endDate, $filters);

        // Map keys to match views (kun, kunlik_savdo, etc.)
        return array_map(function($row) {
            return [
                'kun' => (int) date('j', strtotime($row['sana'])),
                'savdolar_soni' => $row['savdolar_soni'],
                'kunlik_savdo' => $row['jami_savdo'],
                'kunlik_chegirma' => $row['jami_chegirma'],
                'kunlik_qarz' => $row['jami_qarz'],
            ];
        }, $rows);
    }

    /**
     * Savdo hisoboti - umumlashtirilgan
     */
    public function getSalesSummary($startDate, $endDate, $filters = []) {
        $whereConditions = [];
        $params = [$startDate, $endDate];

        // Kassir filtri
        if (!empty($filters['cashier_id'])) {
            $whereConditions[] = "s.kassir_id = ?";
            $params[] = $filters['cashier_id'];
        }

        $whereClause = !empty($whereConditions) ? " AND " . implode(" AND ", $whereConditions) : "";

        $stmt = $this->db->prepare("
            SELECT
                COUNT(DISTINCT s.id) as total_sales,
                SUM(s.yakuniy_summa) as gross_sales,
                SUM(s.chegirma_summa) as total_discounts,
                SUM(CASE WHEN s.tolov_usuli = 'NAQD' THEN s.tolangan_summa ELSE 0 END) as cash_sales,
                SUM(CASE WHEN s.tolov_usuli IN ('KARTA', 'OTKAZMA') THEN s.tolangan_summa ELSE 0 END) as card_sales,
                SUM(CASE WHEN s.tolov_usuli = 'ARALASH' THEN s.tolangan_summa ELSE 0 END) as mixed_sales,
                SUM(s.qarz_summa) as total_debt,
                AVG(s.yakuniy_summa) as average_sale,
                COUNT(DISTINCT s.mijoz_id) as unique_customers
            FROM savdolar s
            WHERE DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN' {$whereClause}
        ");
        $stmt->execute($params);
        $result = $stmt->fetch();

        // Null qiymatlarni 0 ga o'zgartirish
        return array_map(function($value) {
            return $value ?? 0;
        }, $result);
    }

    /**
     * Diller to'lovlari hisoboti
     */
    public function getDealerPayments($startDate, $endDate, $filters = []) {
        $whereConditions = [];
        $params = [$startDate, $endDate];

        // Diller filtri
        if (!empty($filters['dealer_id'])) {
            $whereConditions[] = "yt.yetkazib_beruvchi_id = ?";
            $params[] = $filters['dealer_id'];
        }

        $whereClause = !empty($whereConditions) ? " AND " . implode(" AND ", $whereConditions) : "";

        $stmt = $this->db->prepare("
            SELECT
                y.nomi as dealer_name,
                y.telefon as dealer_phone,
                yt.sana,
                yt.summa,
                yt.usul as payment_method,
                yt.izoh,
                u.fio as received_by
            FROM yetkazib_beruvchi_tolovlari yt
            JOIN yetkazib_beruvchilar y ON yt.yetkazib_beruvchi_id = y.id
            LEFT JOIN foydalanuvchilar u ON yt.qabul_qilgan_id = u.id
            WHERE DATE(yt.sana) BETWEEN ? AND ? {$whereClause}
            ORDER BY yt.sana DESC
        ");
        $stmt->execute($params);
        $payments = $stmt->fetchAll();

        // Jami hisoblash
        $totalPayments = array_sum(array_column($payments, 'summa'));

        return [
            'payments' => $payments,
            'total_payments' => $totalPayments,
            'payment_count' => count($payments)
        ];
    }

    /**
     * Qarz to'lovlari hisoboti
     */
    public function getDebtCollections($startDate, $endDate, $filters = []) {
        $whereConditions = [];
        $params = [$startDate, $endDate];

        // Mijoz filtri
        if (!empty($filters['customer_id'])) {
            $whereConditions[] = "nt.mijoz_id = ?";
            $params[] = $filters['customer_id'];
        }

        $whereClause = !empty($whereConditions) ? " AND " . implode(" AND ", $whereConditions) : "";

        $stmt = $this->db->prepare("
            SELECT
                m.fio as customer_name,
                m.telefon as customer_phone,
                nt.tolov_vaqt,
                nt.summa,
                nt.tolov_usuli as payment_method,
                nt.izoh,
                u.fio as received_by,
                s.chek_raqami as sale_receipt
            FROM nasiya_tolovlar nt
            JOIN mijozlar m ON nt.mijoz_id = m.id
            LEFT JOIN foydalanuvchilar u ON nt.qabul_qilgan_id = u.id
            LEFT JOIN savdolar s ON nt.savdo_id = s.id
            WHERE DATE(nt.tolov_vaqt) BETWEEN ? AND ? {$whereClause}
            ORDER BY nt.tolov_vaqt DESC
        ");
        $stmt->execute($params);
        $collections = $stmt->fetchAll();

        // Jami hisoblash
        $totalCollections = array_sum(array_column($collections, 'summa'));

        return [
            'collections' => $collections,
            'total_collections' => $totalCollections,
            'collection_count' => count($collections)
        ];
    }

    /**
     * Qaytarishlar hisoboti
     */
    public function getReturnsSummary($startDate, $endDate, $filters = []) {
        $whereConditions = [];
        $params = [$startDate, $endDate];

        // Kassir filtri
        if (!empty($filters['cashier_id'])) {
            $whereConditions[] = "q.foydalanuvchi_id = ?";
            $params[] = $filters['cashier_id'];
        }

        $whereClause = !empty($whereConditions) ? " AND " . implode(" AND ", $whereConditions) : "";

        $stmt = $this->db->prepare("
            SELECT
                q.qaytarilgan_vaqt,
                s.chek_raqami as original_receipt,
                p.nomi as product_name,
                q.miqdor,
                q.summa,
                q.sabab,
                u.fio as processed_by,
                m.fio as customer_name
            FROM qaytarishlar q
            JOIN savdolar s ON q.savdo_id = s.id
            JOIN mahsulotlar p ON q.mahsulot_id = p.id
            LEFT JOIN foydalanuvchilar u ON q.foydalanuvchi_id = u.id
            LEFT JOIN mijozlar m ON s.mijoz_id = m.id
            WHERE DATE(q.qaytarilgan_vaqt) BETWEEN ? AND ? {$whereClause}
            ORDER BY q.qaytarilgan_vaqt DESC
        ");
        $stmt->execute($params);
        $returns = $stmt->fetchAll();

        // Jami hisoblash
        $totalReturns = array_sum(array_column($returns, 'summa'));

        return [
            'returns' => $returns,
            'total_returns' => $totalReturns,
            'return_count' => count($returns)
        ];
    }

    /**
     * Kassa hisoboti
     */
    public function getCashSummary($startDate, $endDate, $filters = []) {
        // Boshlang'ich kassa (oxirgi smena boshlang'ich naqdi)
        $openingCash = $this->getOpeningCash($startDate, $filters);

        // Naqd savdo summasi
        $cashSales = $this->getCashSales($startDate, $endDate, $filters);

        // Qarz to'lovlaridan naqd
        $cashDebtCollections = $this->getCashDebtCollections($startDate, $endDate, $filters);

        // Diller to'lovlari (naqd)
        $cashDealerPayments = $this->getCashDealerPayments($startDate, $endDate, $filters);

        // Qaytarishlar (naqd)
        $cashRefunds = $this->getCashRefunds($startDate, $endDate, $filters);

        // Kutilgan kassa hisoblash
        $expectedCash = $openingCash +
                       $cashSales +
                       $cashDebtCollections -
                       $cashDealerPayments -
                       $cashRefunds;

        // Haqiqiy kassa (agar mavjud bo'lsa)
        $actualCash = $this->getActualCash($startDate, $endDate, $filters);

        // Kamomad/ortiqcha
        $difference = null;
        if ($actualCash !== null) {
            $difference = $actualCash - $expectedCash;
        }

        return [
            'opening_cash' => $openingCash,
            'cash_sales' => $cashSales,
            'cash_debt_collections' => $cashDebtCollections,
            'cash_dealer_payments' => $cashDealerPayments,
            'cash_refunds' => $cashRefunds,
            'expected_cash' => $expectedCash,
            'actual_cash' => $actualCash,
            'difference' => $difference,
            'shortage' => ($difference !== null && $difference < 0) ? abs($difference) : 0,
            'overage' => ($difference !== null && $difference > 0) ? $difference : 0
        ];
    }

    /**
     * Boshlang'ich kassa
     */
    public function getOpeningCash($date, $filters = []) {
        $stmt = $this->db->prepare("
            SELECT COALESCE(ochilish_naqd, 0) as opening_cash
            FROM kassa_smenalari
            WHERE DATE(ochilgan_vaqt) <= ? AND (DATE(yopilgan_vaqt) >= ? OR yopilgan_vaqt IS NULL)
            ORDER BY ochilgan_vaqt DESC
            LIMIT 1
        ");
        $stmt->execute([$date, $date]);
        $result = $stmt->fetch();
        return $result ? $result['opening_cash'] : 0;
    }

    /**
     * Naqd savdo summasi
     */
    public function getCashSales($startDate, $endDate, $filters = []) {
        $whereConditions = [];
        $params = [$startDate, $endDate];

        if (!empty($filters['cashier_id'])) {
            $whereConditions[] = "s.kassir_id = ?";
            $params[] = $filters['cashier_id'];
        }

        $whereClause = !empty($whereConditions) ? " AND " . implode(" AND ", $whereConditions) : "";

        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(
                CASE
                    WHEN s.tolov_usuli = 'NAQD' THEN s.tolangan_summa
                    WHEN s.tolov_usuli = 'ARALASH' THEN s.tolangan_summa
                    ELSE 0
                END
            ), 0) as cash_sales
            FROM savdolar s
            WHERE DATE(s.sotilgan_vaqt) BETWEEN ? AND ? AND s.holat = 'YAKUNLANGAN' {$whereClause}
        ");
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['cash_sales'];
    }

    /**
     * Qarz to'lovlaridan naqd
     */
    public function getCashDebtCollections($startDate, $endDate, $filters = []) {
        $whereConditions = [];
        $params = [$startDate, $endDate];

        if (!empty($filters['customer_id'])) {
            $whereConditions[] = "nt.mijoz_id = ?";
            $params[] = $filters['customer_id'];
        }

        $whereClause = !empty($whereConditions) ? " AND " . implode(" AND ", $whereConditions) : "";

        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(nt.summa), 0) as cash_collections
            FROM nasiya_tolovlar nt
            WHERE DATE(nt.tolov_vaqt) BETWEEN ? AND ? AND nt.tolov_usuli = 'NAQD' {$whereClause}
        ");
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['cash_collections'];
    }

    /**
     * Diller to'lovlari (naqd)
     */
    public function getCashDealerPayments($startDate, $endDate, $filters = []) {
        $whereConditions = [];
        $params = [$startDate, $endDate];

        if (!empty($filters['dealer_id'])) {
            $whereConditions[] = "yt.yetkazib_beruvchi_id = ?";
            $params[] = $filters['dealer_id'];
        }

        $whereClause = !empty($whereConditions) ? " AND " . implode(" AND ", $whereConditions) : "";

        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(yt.summa), 0) as cash_payments
            FROM yetkazib_beruvchi_tolovlari yt
            WHERE DATE(yt.sana) BETWEEN ? AND ? AND yt.usul = 'NAQD' {$whereClause}
        ");
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['cash_payments'];
    }

    /**
     * Qaytarishlar (naqd)
     */
    public function getCashRefunds($startDate, $endDate, $filters = []) {
        $whereConditions = [];
        $params = [$startDate, $endDate];

        if (!empty($filters['cashier_id'])) {
            $whereConditions[] = "q.foydalanuvchi_id = ?";
            $params[] = $filters['cashier_id'];
        }

        $whereClause = !empty($whereConditions) ? " AND " . implode(" AND ", $whereConditions) : "";

        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(q.summa), 0) as cash_refunds
            FROM qaytarishlar q
            WHERE DATE(q.qaytarilgan_vaqt) BETWEEN ? AND ? {$whereClause}
        ");
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['cash_refunds'];
    }

    /**
     * Haqiqiy kassa (actual cash)
     */
    public function getActualCash($startDate, $endDate, $filters = []) {
        // Ensure the schema has the expected column.
        if (!$this->columnExists('kassa_smenalari', 'actual_cash')) {
            $this->createActualCashColumn();
            // If creation fails or column still missing, return null so report continues.
            if (!$this->columnExists('kassa_smenalari', 'actual_cash')) {
                return null;
            }
        }

        $stmt = $this->db->prepare("
            SELECT COALESCE(actual_cash, 0) as actual_cash
            FROM kassa_smenalari
            WHERE DATE(yopilgan_vaqt) BETWEEN ? AND ? AND actual_cash IS NOT NULL
            ORDER BY yopilgan_vaqt DESC
            LIMIT 1
        ");
        $stmt->execute([$startDate, $endDate]);
        $result = $stmt->fetch();
        return $result ? $result['actual_cash'] : null;
    }

    /**
     * Check whether a column exists on a given table.
     */
    private function columnExists($table, $column) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?");
        $stmt->execute([$table, $column]);
        $result = $stmt->fetch();
        return !empty($result) && intval($result['cnt']) > 0;
    }

    /**
     * Attempt to create the actual_cash column if it is missing.
     */
    private function createActualCashColumn() {
        try {
            $this->db->exec("ALTER TABLE kassa_smenalari ADD COLUMN IF NOT EXISTS actual_cash DECIMAL(12,2) NULL DEFAULT NULL AFTER yopilish_naqd;");
        } catch (\PDOException $e) {
            // Ignore failures - some MySQL versions do not support IF NOT EXISTS in ALTER.
            try {
                $this->db->exec("ALTER TABLE kassa_smenalari ADD COLUMN actual_cash DECIMAL(12,2) NULL DEFAULT NULL AFTER yopilish_naqd;");
            } catch (\PDOException $e) {
                // Quietly ignore; report will continue without actual cash
            }
        }
    }

    /**
     * Excel export uchun ma'lumotlarni tayyorlash
     */
    public function getReportRowsForExcel($type, $startDate, $endDate, $filters = []) {
        switch ($type) {
            case 'daily':
                return $this->getDailyReportRows($startDate, $filters);
            case 'weekly':
                return $this->getWeeklyReportRows($startDate, $endDate, $filters);
            case 'monthly':
                return $this->getMonthlyReportRows($startDate, $endDate, $filters);
            default:
                return [];
        }
    }

    /**
     * PDF export uchun ma'lumotlarni tayyorlash
     */
    public function getReportRowsForPdf($type, $startDate, $endDate, $filters = []) {
        return $this->getReportRowsForExcel($type, $startDate, $endDate, $filters);
    }

    /**
     * Kunlik hisobot qatorlari
     */
    private function getDailyReportRows($date, $filters = []) {
        $report = $this->getDailyReport($date, $filters);

        $rows = [];

        // Summary
        $rows[] = ['SUMMARY', '', '', ''];
        $rows[] = ['Gross Sales', $report['sales']['gross_sales'], '', ''];
        $rows[] = ['Returns', $report['returns']['total_returns'], '', ''];
        $rows[] = ['Net Sales', $report['sales']['gross_sales'] - $report['returns']['total_returns'], '', ''];
        $rows[] = ['Cash Sales', $report['sales']['cash_sales'], '', ''];
        $rows[] = ['Card Sales', $report['sales']['card_sales'], '', ''];
        $rows[] = ['Debt Collections', $report['debt_collections']['total_collections'], '', ''];
        $rows[] = ['Dealer Payments', $report['dealer_payments']['total_payments'], '', ''];
        $rows[] = ['Opening Cash', $report['cash']['opening_cash'], '', ''];
        $rows[] = ['Expected Cash', $report['cash']['expected_cash'], '', ''];
        if ($report['cash']['actual_cash'] !== null) {
            $rows[] = ['Actual Cash', $report['cash']['actual_cash'], '', ''];
            $rows[] = ['Difference', $report['cash']['difference'], '', ''];
        }

        $rows[] = ['', '', '', ''];

        // Sales details
        $rows[] = ['SALES DETAILS', '', '', ''];
        $rows[] = ['Date', 'Receipt', 'Cashier', 'Amount'];
        foreach ($this->getDailySales($date) as $sale) {
            $rows[] = [
                $sale['sotilgan_vaqt'],
                $sale['chek_raqami'],
                $sale['kassir_fio'],
                $sale['yakuniy_summa']
            ];
        }

        $rows[] = ['', '', '', ''];

        // Dealer payments
        $rows[] = ['DEALER PAYMENTS', '', '', ''];
        $rows[] = ['Dealer', 'Date', 'Amount', 'Method'];
        foreach ($report['dealer_payments']['payments'] as $payment) {
            $rows[] = [
                $payment['dealer_name'],
                $payment['sana'],
                $payment['summa'],
                $payment['payment_method']
            ];
        }

        $rows[] = ['', '', '', ''];

        // Debt collections
        $rows[] = ['DEBT COLLECTIONS', '', '', ''];
        $rows[] = ['Customer', 'Date', 'Amount', 'Method'];
        foreach ($report['debt_collections']['collections'] as $collection) {
            $rows[] = [
                $collection['customer_name'],
                $collection['tolov_vaqt'],
                $collection['summa'],
                $collection['payment_method']
            ];
        }

        return $rows;
    }

    /**
     * Haftalik hisobot qatorlari
     */
    private function getWeeklyReportRows($startDate, $endDate, $filters = []) {
        $report = $this->getWeeklyReport($startDate, $endDate, $filters);

        $rows = [];

        // Summary
        $rows[] = ['WEEKLY SUMMARY', '', '', ''];
        $rows[] = ['Period', $startDate . ' - ' . $endDate, '', ''];
        $rows[] = ['Gross Sales', $report['sales']['gross_sales'], '', ''];
        $rows[] = ['Returns', $report['returns']['total_returns'], '', ''];
        $rows[] = ['Net Sales', $report['sales']['gross_sales'] - $report['returns']['total_returns'], '', ''];
        $rows[] = ['Cash Sales', $report['sales']['cash_sales'], '', ''];
        $rows[] = ['Card Sales', $report['sales']['card_sales'], '', ''];
        $rows[] = ['Debt Collections', $report['debt_collections']['total_collections'], '', ''];
        $rows[] = ['Dealer Payments', $report['dealer_payments']['total_payments'], '', ''];
        $rows[] = ['Expected Cash', $report['cash']['expected_cash'], '', ''];

        return $rows;
    }

    /**
     * Oylik hisobot qatorlari
     */
    private function getMonthlyReportRows($startDate, $endDate, $filters = []) {
        $report = $this->getWeeklyReport($startDate, $endDate, $filters); // Reusing weekly logic

        $rows = [];

        // Summary
        $rows[] = ['MONTHLY SUMMARY', '', '', ''];
        $rows[] = ['Period', $startDate . ' - ' . $endDate, '', ''];
        $rows[] = ['Gross Sales', $report['sales']['gross_sales'], '', ''];
        $rows[] = ['Returns', $report['returns']['total_returns'], '', ''];
        $rows[] = ['Net Sales', $report['sales']['gross_sales'] - $report['returns']['total_returns'], '', ''];
        $rows[] = ['Cash Sales', $report['sales']['cash_sales'], '', ''];
        $rows[] = ['Card Sales', $report['sales']['card_sales'], '', ''];
        $rows[] = ['Debt Collections', $report['debt_collections']['total_collections'], '', ''];
        $rows[] = ['Dealer Payments', $report['dealer_payments']['total_payments'], '', ''];
        $rows[] = ['Expected Cash', $report['cash']['expected_cash'], '', ''];

        return $rows;
    }
}