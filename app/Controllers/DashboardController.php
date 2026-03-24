<?php
// app/Controllers/DashboardController.php

namespace App\Controllers;

use App\Models\Dashboard;
use App\Models\Product;

class DashboardController extends Controller
{
    private $dashboardModel;
    private $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->dashboardModel = new Dashboard();
        $this->productModel = new Product();
    }

    /**
     * Dashboard asosiy sahifasi
     */
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
            return;
        }

        // Faqat admin ruxsati
        if (($_SESSION['user']['rol_nomi'] ?? '') !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda bu sahifaga kirish ruxsati yo\'q';
            $this->redirect('pos');
            return;
        }

        // Asosiy statistikalar (qaytarishlar bilan birga)
        $stats = $this->dashboardModel->getStats();

        // Boshqa statistikalar
        $lowStockProducts = $this->dashboardModel->getLowStockProducts(10);
        $topProducts = $this->dashboardModel->getTopProducts(5);
        $recentSales = $this->dashboardModel->getRecentSales(10, true);
        $topDebtors = $this->dashboardModel->getTopDebtors(5);
        $cashierRanking = $this->dashboardModel->getCashierRanking(5);
        $dailyStats = $this->dashboardModel->getDailyStats(7);

        // Diller va ombor qo‘shimcha statistikalari
        $supplierDebtStats = method_exists($this->dashboardModel, 'getSupplierDebtStats')
            ? $this->dashboardModel->getSupplierDebtStats()
            : [];

        $stockCostStats = method_exists($this->dashboardModel, 'getStockCostStats')
            ? $this->dashboardModel->getStockCostStats()
            : [];

        // total bo‘limi mavjudligini ta’minlash
        if (!isset($stats['total']) || !is_array($stats['total'])) {
            $stats['total'] = [];
        }

        // Dillerlar qarzi
        $stats['total']['jami_yetkazib_beruvchi_qarzi'] =
            (float)($supplierDebtStats['jami_yetkazib_beruvchi_qarzi'] ?? 0);

        $stats['total']['qarzdor_yetkazib_beruvchilar_soni'] =
            (int)($supplierDebtStats['qarzdor_yetkazib_beruvchilar_soni'] ?? 0);

        // Ombordagi jami tannarx va miqdor
        $stats['total']['jami_ombor_tannarxi'] =
            (float)($stockCostStats['jami_ombor_tannarxi'] ?? 0);

        $stats['total']['jami_ombor_miqdori'] =
            (int)($stockCostStats['jami_ombor_miqdori'] ?? 0);

        // Kam qolgan mahsulotlar soni
        $lowStockCount = count($lowStockProducts);

        $this->view('dashboard/index', [
            'stats'            => $stats,
            'lowStockProducts' => $lowStockProducts,
            'lowStockCount'    => $lowStockCount,
            'topProducts'      => $topProducts,
            'recentSales'      => $recentSales,
            'topDebtors'       => $topDebtors,
            'cashierRanking'   => $cashierRanking,
            'dailyStats'       => $dailyStats
        ]);
    }

    /**
     * AJAX orqali statistikani yangilash
     */
    public function refreshStats()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->json([
                'success' => false,
                'message' => 'Avval tizimga kiring'
            ]);
            return;
        }

        $stats = $this->dashboardModel->getStats();

        $supplierDebtStats = method_exists($this->dashboardModel, 'getSupplierDebtStats')
            ? $this->dashboardModel->getSupplierDebtStats()
            : [];

        $stockCostStats = method_exists($this->dashboardModel, 'getStockCostStats')
            ? $this->dashboardModel->getStockCostStats()
            : [];

        if (!isset($stats['total']) || !is_array($stats['total'])) {
            $stats['total'] = [];
        }

        $stats['total']['jami_yetkazib_beruvchi_qarzi'] =
            (float)($supplierDebtStats['jami_yetkazib_beruvchi_qarzi'] ?? 0);

        $stats['total']['qarzdor_yetkazib_beruvchilar_soni'] =
            (int)($supplierDebtStats['qarzdor_yetkazib_beruvchilar_soni'] ?? 0);

        $stats['total']['jami_ombor_tannarxi'] =
            (float)($stockCostStats['jami_ombor_tannarxi'] ?? 0);

        $stats['total']['jami_ombor_miqdori'] =
            (int)($stockCostStats['jami_ombor_miqdori'] ?? 0);

        $lowStockCount = count($this->dashboardModel->getLowStockProducts(10));

        $this->json([
            'success'       => true,
            'stats'         => $stats,
            'lowStockCount' => $lowStockCount
        ]);
    }
}