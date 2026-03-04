<?php
// app/Controllers/ReportController.php

namespace App\Controllers;

use App\Models\Report;

class ReportController extends Controller {
    
    private $reportModel;
    
    public function __construct() {
        parent::__construct();
        $this->reportModel = new Report();
    }
    
    /**
     * Hisobotlar asosiy sahifasi
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        // Bugungi sana
        $today = date('Y-m-d');
        
        // Bugungi hisobot
        $dailyReport = $this->reportModel->getDailyReport($today);
        $dailySales = $this->reportModel->getDailySales($today);
        
        // Joriy oy va yil
        $currentYear = date('Y');
        $currentMonth = date('m');
        
        // Oylik hisobot
        $monthlyReport = $this->reportModel->getMonthlyReport($currentYear, $currentMonth);
        
        // Top mahsulotlar (oxirgi 30 kun)
        $startDate = date('Y-m-d', strtotime('-30 days'));
        $endDate = $today;
        $topProducts = $this->reportModel->getTopProducts($startDate, $endDate, 10);
        
        // Qarzdorlar
        $debtors = $this->reportModel->getDebtReport();
        
        $this->view('reports/index', [
            'dailyReport' => $dailyReport,
            'dailySales' => $dailySales,
            'monthlyReport' => $monthlyReport,
            'topProducts' => $topProducts,
            'debtors' => $debtors,
            'today' => $today,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth
        ]);
    }
    
    /**
     * Kunlik hisobot (AJAX)
     */
    public function daily() {
        $date = $_GET['date'] ?? date('Y-m-d');
        
        $report = $this->reportModel->getDailyReport($date);
        $sales = $this->reportModel->getDailySales($date);
        
        $this->json([
            'report' => $report,
            'sales' => $sales
        ]);
    }
    
    /**
     * Oylik hisobot
     */
    public function monthly() {
        $year = $_GET['year'] ?? date('Y');
        $month = $_GET['month'] ?? date('m');
        
        $report = $this->reportModel->getMonthlyReport($year, $month);
        
        // Oy nomi
        $monthNames = [
            1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
            5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
            9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr'
        ];
        
        $this->view('reports/monthly', [
            'report' => $report,
            'year' => $year,
            'month' => $month,
            'monthName' => $monthNames[(int)$month] ?? 'Noma\'lum'
        ]);
    }
    
    /**
     * Foyda hisoboti
     */
    public function profit() {
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $profitReport = $this->reportModel->getProfitReport($startDate, $endDate);
        
        // Umumiy hisob
        $totalSales = 0;
        $totalProfit = 0;
        $totalCost = 0;
        
        foreach ($profitReport as $item) {
            $totalSales += $item['tushum'];
            $totalProfit += $item['yalpi_foyda'];
            $totalCost += $item['tannarx'];
        }
        
        $averageProfitPercent = $totalSales > 0 ? ($totalProfit / $totalSales * 100) : 0;
        
        $this->view('reports/profit', [
            'profitReport' => $profitReport,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalSales' => $totalSales,
            'totalProfit' => $totalProfit,
            'totalCost' => $totalCost,
            'averageProfitPercent' => $averageProfitPercent
        ]);
    }
    
    /**
     * Top mahsulotlar hisoboti
     */
    public function topProducts() {
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $limit = $_GET['limit'] ?? 20;
        
        $products = $this->reportModel->getTopProducts($startDate, $endDate, $limit);
        
        $this->view('reports/top-products', [
            'products' => $products,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'limit' => $limit
        ]);
    }
    
    /**
     * Kassir hisoboti
     */
    public function cashiers() {
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $cashiers = $this->reportModel->getCashierReport($startDate, $endDate);
        
        $this->view('reports/cashiers', [
            'cashiers' => $cashiers,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    
    /**
     * Qarzdorlar hisoboti
     */
    public function debtors() {
        $debtors = $this->reportModel->getDebtReport();
        
        $this->view('reports/debtors', [
            'debtors' => $debtors
        ]);
    }
    
    /**
     * Kategoriyalar hisoboti
     */
    public function categories() {
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $categories = $this->reportModel->getCategoryReport($startDate, $endDate);
        
        $this->view('reports/categories', [
            'categories' => $categories,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    
    /**
     * Excel export (qo'shimcha)
     */
    public function exportExcel($type) {
        // Excel export funksiyasi (keyinroq qo'shamiz)
        $_SESSION['flash']['error'] = 'Excel export hozircha tayyor emas';
        $this->redirect('reports');
    }
    
    /**
     * PDF export (qo'shimcha)
     */
    public function exportPdf($type) {
        // PDF export funksiyasi (keyinroq qo'shamiz)
        $_SESSION['flash']['error'] = 'PDF export hozircha tayyor emas';
        $this->redirect('reports');
    }
}