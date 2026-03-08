<?php
// app/Controllers/DashboardController.php

namespace App\Controllers;

use App\Models\Dashboard;
use App\Models\Product;

class DashboardController extends Controller {
    
    private $dashboardModel;
    private $productModel;
    
    public function __construct() {
        parent::__construct();
        $this->dashboardModel = new Dashboard();
        $this->productModel = new Product();
    }
    
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda bu sahifaga kirish ruxsati yo\'q';
            $this->redirect('pos');
        }
        
        // Barcha statistik ma'lumotlarni olish
        $stats = $this->dashboardModel->getStats();
        $lowStockProducts = $this->dashboardModel->getLowStockProducts(10);
        $topProducts = $this->dashboardModel->getTopProducts(5);
        $recentSales = $this->dashboardModel->getRecentSales(10, true);
        $topDebtors = $this->dashboardModel->getTopDebtors(5);
        $cashierRanking = $this->dashboardModel->getCashierRanking(5);
        $dailyStats = $this->dashboardModel->getDailyStats(7);
       
        
        // Kam qolgan mahsulotlar soni
        $lowStockCount = count($lowStockProducts);
        
        $this->view('dashboard/index', [
            'stats' => $stats,
            'lowStockProducts' => $lowStockProducts,
            'lowStockCount' => $lowStockCount,
            'topProducts' => $topProducts,
            'recentSales' => $recentSales,
            'topDebtors' => $topDebtors,
            'cashierRanking' => $cashierRanking,
            'dailyStats' => $dailyStats
        ]);
    }
    
    /**
     * AJAX orqali statistikani yangilash
     */
    public function refreshStats() {
        $stats = $this->dashboardModel->getStats();
        $lowStockCount = count($this->dashboardModel->getLowStockProducts(10));
        
        $this->json([
            'success' => true,
            'stats' => $stats,
            'lowStockCount' => $lowStockCount
        ]);
    }
}