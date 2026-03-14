<?php
// app/Models/Dashboard.php

namespace App\Models;

class Dashboard extends Model {
    
    /**
     * Asosiy statistik ma'lumotlar
     */
    public function getStats() {
        $today = date('Y-m-d');
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        
        $stats = [];
        
        // 1. Bugungi statistika
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as bugungi_savdolar,
                IFNULL(SUM(yakuniy_summa), 0) as bugungi_tushum,
                IFNULL(SUM(qarz_summa), 0) as bugungi_qarz,
                COUNT(DISTINCT kassir_id) as faol_kassirlar
            FROM savdolar 
            WHERE DATE(sotilgan_vaqt) = ? AND holat = 'YAKUNLANGAN'
        ");
        $stmt->execute([$today]);
        $stats['today'] = $stmt->fetch();
        
        // 2. Oylik statistika
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as oylik_savdolar,
                IFNULL(SUM(yakuniy_summa), 0) as oylik_tushum,
                IFNULL(SUM(qarz_summa), 0) as oylik_qarz,
                IFNULL(AVG(yakuniy_summa), 0) as ortacha_chek
            FROM savdolar 
            WHERE DATE(sotilgan_vaqt) BETWEEN ? AND ? AND holat = 'YAKUNLANGAN'
        ");
        $stmt->execute([$startOfMonth, $endOfMonth]);
        $stats['monthly'] = $stmt->fetch();
        
        // 3. Jami statistika
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as jami_savdolar,
                IFNULL(SUM(yakuniy_summa), 0) as jami_tushum,
                IFNULL(SUM(qarz_summa), 0) as jami_qarz,
                COUNT(DISTINCT kassir_id) as jami_kassirlar
            FROM savdolar 
            WHERE holat = 'YAKUNLANGAN'
        ");
        $stats['total'] = $stmt->fetch();
        
        // 4. Oxirgi 7 kunlik savdo (grafik uchun)
        $stmt = $this->db->prepare("
            SELECT 
                DATE(sotilgan_vaqt) as sana,
                COUNT(*) as savdolar_soni,
                SUM(yakuniy_summa) as kunlik_summa
            FROM savdolar 
            WHERE sotilgan_vaqt >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
                AND holat = 'YAKUNLANGAN'
            GROUP BY DATE(sotilgan_vaqt)
            ORDER BY sana ASC
        ");
        $stmt->execute();
        $stats['weekly'] = $stmt->fetchAll();
        
        // 5. Top kategoriyalar
        $stmt = $this->db->query("
            SELECT 
                k.nomi,
                COUNT(DISTINCT s.id) as savdolar_soni,
                SUM(st.soni) as sotilgan_soni,
                SUM(st.qator_summa) as jami_summa
            FROM kategoriyalar k
            LEFT JOIN mahsulotlar p ON k.id = p.kategoriya_id
            LEFT JOIN savdo_tarkibi st ON p.id = st.mahsulot_id
            LEFT JOIN savdolar s ON st.savdo_id = s.id AND s.holat = 'YAKUNLANGAN'
            GROUP BY k.id
            ORDER BY jami_summa DESC
            LIMIT 5
        ");
        $stmt->execute();
        $stats['topCategories'] = $stmt->fetchAll();
        
        // 6. Diller statistikasi
        $stmt = $this->db->prepare("
            SELECT 
                IFNULL(SUM(jami_olingan), 0) as dillerlarga_berilgan,
                IFNULL(SUM(jami_tolangan), 0) as dillerlardan_tushgan,
                IFNULL(SUM(qarz), 0) as diller_qarzi
            FROM yetkazib_beruvchilar
            WHERE faol = 1
        ");
        $stmt->execute();
        $stats['dealers'] = $stmt->fetch();
        
        return $stats;
    }
    
   /**
 * Kam qolgan mahsulotlar ro'yxati - TUZATILGAN
 */
    public function getLowStockProducts($limit = 10) {
        // LIMIT ni to'g'ridan-to'g'ri so'rovga qo'shamiz
        $limit = intval($limit); // Xavfsizlik uchun integer ga aylantiramiz
        
        $sql = "
            SELECT 
                p.*,
                k.nomi as kategoriya_nomi,
                (p.minimal_miqdor - p.miqdor) as yetmayotgan,
                CASE 
                    WHEN p.miqdor = 0 THEN 'danger'
                    WHEN p.miqdor <= p.minimal_miqdor/2 THEN 'warning'
                    ELSE 'info'
                END as holat_darajasi
            FROM mahsulotlar p
            LEFT JOIN kategoriyalar k ON p.kategoriya_id = k.id
            WHERE p.faol = 1 
                AND p.ochirilgan_vaqt IS NULL 
                AND p.miqdor <= p.minimal_miqdor
            ORDER BY (p.miqdor / p.minimal_miqdor) ASC, p.miqdor ASC
            LIMIT {$limit}
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    /**
 * Top mahsulotlar (eng ko'p sotilgan) - TUZATILGAN
 */
public function getTopProducts($limit = 5) {
    $limit = intval($limit);
    
    $sql = "
        SELECT 
            p.id,
            p.nomi,
            p.shtrix_kod,
            p.birlik,
            k.nomi as kategoriya_nomi,
            COUNT(DISTINCT s.id) as savdolar_soni,
            SUM(st.soni) as jami_sotilgan,
            SUM(st.qator_summa) as jami_tushum
        FROM mahsulotlar p
        JOIN savdo_tarkibi st ON p.id = st.mahsulot_id
        JOIN savdolar s ON st.savdo_id = s.id
        LEFT JOIN kategoriyalar k ON p.kategoriya_id = k.id
        WHERE s.holat = 'YAKUNLANGAN'
            AND s.sotilgan_vaqt >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY p.id
        ORDER BY jami_sotilgan DESC
        LIMIT {$limit}
    ";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Oxirgi savdolar (qaytarilganlar bilan birga)
 */
public function getRecentSales($limit = 10, $showCancelled = true) {
    $sql = "
        SELECT 
            s.id,
            s.chek_raqami,
            s.sotilgan_vaqt,
            s.yakuniy_summa,
            s.tolov_usuli,
            s.tolov_holati,
            s.holat,
            u.fio as kassir_fio,
            m.fio as mijoz_fio,
            (SELECT COUNT(*) FROM savdo_tarkibi WHERE savdo_id = s.id) as mahsulotlar_soni
        FROM savdolar s
        LEFT JOIN foydalanuvchilar u ON s.kassir_id = u.id
        LEFT JOIN mijozlar m ON s.mijoz_id = m.id
        WHERE 1=1
    ";
    
    // Agar bekor qilinganlarni ham ko'rsatmoqchi bo'lsak
    if (!$showCancelled) {
        $sql .= " AND s.holat = 'YAKUNLANGAN'";
    }
    
    $sql .= " ORDER BY s.sotilgan_vaqt DESC LIMIT " . intval($limit);
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}
/**
 * Qarzdorlar (eng katta qarzlar) - TUZATILGAN
 */
public function getTopDebtors($limit = 5) {
    $limit = intval($limit);
    
    $sql = "
        SELECT 
            m.id,
            m.fio,
            m.telefon,
            COUNT(DISTINCT s.id) as qarzli_savdolar,
            SUM(s.qarz_summa) as jami_qarz,
            MAX(s.sotilgan_vaqt) as oxirgi_savdo
        FROM mijozlar m
        JOIN savdolar s ON m.id = s.mijoz_id
        WHERE s.tolov_holati IN ('NASIYA', 'QISMAN') 
            AND s.qarz_summa > 0
        GROUP BY m.id
        ORDER BY jami_qarz DESC
        LIMIT {$limit}
    ";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Kunlik statistika (oxirgi 30 kun) - TUZATILGAN
 */
public function getDailyStats($days = 30) {
    $days = intval($days);
    
    $sql = "
        SELECT 
            DATE(sotilgan_vaqt) as sana,
            COUNT(*) as savdolar_soni,
            SUM(yakuniy_summa) as jami_tushum,
            SUM(qarz_summa) as jami_qarz,
            AVG(yakuniy_summa) as ortacha_chek
        FROM savdolar
        WHERE sotilgan_vaqt >= DATE_SUB(NOW(), INTERVAL {$days} DAY)
            AND holat = 'YAKUNLANGAN'
        GROUP BY DATE(sotilgan_vaqt)
        ORDER BY sana DESC
    ";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Kassirlar reytingi - TUZATILGAN
 */
public function getCashierRanking($limit = 5) {
    $limit = intval($limit);
    
    $sql = "
        SELECT 
            u.id,
            u.fio,
            COUNT(s.id) as savdolar_soni,
            IFNULL(SUM(s.yakuniy_summa), 0) as jami_savdo,
            IFNULL(AVG(s.yakuniy_summa), 0) as ortacha_chek
        FROM foydalanuvchilar u
        LEFT JOIN savdolar s ON u.id = s.kassir_id 
            AND s.holat = 'YAKUNLANGAN'
            AND s.sotilgan_vaqt >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        WHERE u.rol_id IN (1, 2) -- Admin va Kassir
        GROUP BY u.id
        ORDER BY jami_savdo DESC
        LIMIT {$limit}
    ";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}
    /**
     * Jami mahsulotlar sonini olish
     */
    public function getTotalProducts() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM mahsulotlar WHERE ochirilgan_vaqt IS NULL");
        return $stmt->fetch()['count'];
    }

    /**
     * Jami mijozlar sonini olish
     */
    public function getTotalCustomers() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM mijozlar WHERE ochirilgan_vaqt IS NULL");
        return $stmt->fetch()['count'];
    }

    /**
     * Faol kassirlar sonini olish
     */
    public function getActiveCashiers() {
        $stmt = $this->db->query("
            SELECT COUNT(*) as count FROM foydalanuvchilar 
            WHERE rol_id IN (1, 2) AND faol = 1 AND ochirilgan_vaqt IS NULL
        ");
        return $stmt->fetch()['count'];
    }

    /**
     * Oxirgi 30 kundagi o'rtacha savdo
     */
    public function getAverageDailySales() {
        $stmt = $this->db->query("
            SELECT AVG(daily_total) as avg_daily
            FROM (
                SELECT DATE(sotilgan_vaqt) as sale_date, SUM(yakuniy_summa) as daily_total
                FROM savdolar
                WHERE sotilgan_vaqt >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                AND holat = 'YAKUNLANGAN'
                GROUP BY DATE(sotilgan_vaqt)
            ) as daily_sales
        ");
        return $stmt->fetch()['avg_daily'] ?? 0;
    }
    public function getSupplierDebtStats()
    {
        $sql = "
            SELECT
                COALESCE(SUM(
                    CASE
                        WHEN qarz > 0 THEN qarz
                        WHEN jami_olingan > jami_tolangan THEN (jami_olingan - jami_tolangan)
                        ELSE 0
                    END
                ), 0) AS jami_yetkazib_beruvchi_qarzi,
                COALESCE(SUM(
                    CASE
                        WHEN (qarz > 0) OR (jami_olingan > jami_tolangan) THEN 1
                        ELSE 0
                    END
                ), 0) AS qarzdor_yetkazib_beruvchilar_soni
            FROM yetkazib_beruvchilar
            WHERE faol = 1
            AND ochirilgan_vaqt IS NULL
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    public function getStockCostStats()
    {
        $sql = "
            SELECT
                COALESCE(SUM(miqdor * kelish_narxi), 0) AS jami_ombor_tannarxi,
                COALESCE(SUM(miqdor), 0) AS jami_ombor_miqdori
            FROM mahsulotlar
            WHERE faol = 1
            AND ochirilgan_vaqt IS NULL
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }
}