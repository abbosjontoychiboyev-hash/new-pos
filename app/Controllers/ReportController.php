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
        
        // Oylik hisobot (kunlik tafsilotlar)
        $monthlyReport = $this->reportModel->getMonthlyDailyReport($currentYear, $currentMonth);
        
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
     * Kunlik hisobot sahifasi
     */
    public function daily() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $date = $_GET['date'] ?? date('Y-m-d');
        $cashierId = $_GET['cashier_id'] ?? null;
        $dealerId = $_GET['dealer_id'] ?? null;

        $filters = [];
        if ($cashierId) $filters['cashier_id'] = $cashierId;
        if ($dealerId) $filters['dealer_id'] = $dealerId;

        $report = $this->reportModel->getDailyReport($date, $filters);

        // Kassirlar ro'yxati
        $cashiers = $this->getCashiersList();

        // Dillerlar ro'yxati
        $dealers = $this->getDealersList();

        $this->view('reports/daily', [
            'report' => $report,
            'date' => $date,
            'filters' => $filters,
            'cashiers' => $cashiers,
            'dealers' => $dealers
        ]);
    }

    /**
     * Haftalik hisobot sahifasi
     */
    public function weekly() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $weekStart = $_GET['week_start'] ?? date('Y-m-d', strtotime('monday this week'));
        $weekEnd = $_GET['week_end'] ?? date('Y-m-d', strtotime('sunday this week'));
        $cashierId = $_GET['cashier_id'] ?? null;
        $dealerId = $_GET['dealer_id'] ?? null;

        $filters = [];
        if ($cashierId) $filters['cashier_id'] = $cashierId;
        if ($dealerId) $filters['dealer_id'] = $dealerId;

        $report = $this->reportModel->getWeeklyReport($weekStart, $weekEnd, $filters);

        // Kassirlar ro'yxati
        $cashiers = $this->getCashiersList();

        // Dillerlar ro'yxati
        $dealers = $this->getDealersList();

        $this->view('reports/weekly', [
            'report' => $report,
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
            'filters' => $filters,
            'cashiers' => $cashiers,
            'dealers' => $dealers
        ]);
    }

    /**
     * Oylik hisobot sahifasi
     */
    public function monthly() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $year = $_GET['year'] ?? date('Y');
        $month = $_GET['month'] ?? date('m');
        $cashierId = $_GET['cashier_id'] ?? null;
        $dealerId = $_GET['dealer_id'] ?? null;

        $filters = [];
        if ($cashierId) $filters['cashier_id'] = $cashierId;
        if ($dealerId) $filters['dealer_id'] = $dealerId;

        // Kunlik tafsilotlar
        $report = $this->reportModel->getMonthlyDailyReport($year, $month, $filters) ?? [];
        $summary = $this->reportModel->getMonthlyReport($year, $month, $filters) ?? [];

        // Oy nomlari
        $monthNames = [
            1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
            5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
            9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr'
        ];

        // Kassirlar ro'yxati
        $cashiers = $this->getCashiersList();

        // Dillerlar ro'yxati
        $dealers = $this->getDealersList();

        $startDate = sprintf('%04d-%02d-01', $year, $month);

        $this->view('reports/monthly', [
            'report' => $report,
            'summary' => $summary,
            'year' => $year,
            'month' => $month,
            'month_name' => $monthNames[(int)$month] ?? 'Noma\'lum',
            'start_date' => $startDate,
            'filters' => $filters,
            'cashiers' => $cashiers,
            'dealers' => $dealers
        ]);
    }

    /**
     * Kassirlar ro'yxatini olish
     */
    private function getCashiersList() {
        $stmt = $this->db->prepare("
            SELECT id, fio
            FROM foydalanuvchilar
            WHERE rol_id IN (1, 2) AND ochirilgan_vaqt IS NULL
            ORDER BY fio ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Dillerlar ro'yxatini olish
     */
    private function getDealersList() {
        $stmt = $this->db->prepare("
            SELECT id, nomi
            FROM yetkazib_beruvchilar
            WHERE ochirilgan_vaqt IS NULL
            ORDER BY nomi ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Print sahifasi
     */
    public function print() {
        $type = $_GET['type'] ?? 'daily';
        $date = $_GET['date'] ?? date('Y-m-d');
        $startDate = $_GET['start_date'] ?? $date;
        $endDate = $_GET['end_date'] ?? $date;
        $cashierId = $_GET['cashier_id'] ?? null;
        $dealerId = $_GET['dealer_id'] ?? null;

        $filters = [];
        if ($cashierId) $filters['cashier_id'] = $cashierId;
        if ($dealerId) $filters['dealer_id'] = $dealerId;

        switch ($type) {
            case 'daily':
                $report = $this->reportModel->getDailyReport($date, $filters);
                $view = 'reports/print/daily';
                break;
            case 'weekly':
                $report = $this->reportModel->getWeeklyReport($startDate, $endDate, $filters);
                $view = 'reports/print/weekly';
                break;
            case 'monthly':
                $year = date('Y', strtotime($startDate));
                $month = date('m', strtotime($startDate));
                $report = $this->reportModel->getMonthlyReport($year, $month, $filters);
                $view = 'reports/print/monthly';
                break;
            default:
                $this->redirect('reports');
                return;
        }

        $this->view($view, [
            'report' => $report,
            'type' => $type,
            'date' => $date,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'filters' => $filters
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
     * Dillerlar hisobot
     */
    public function dealers() {
        $dealers = $this->reportModel->getDealerReport();
        $this->view('reports/dealers', [
            'dealers' => $dealers
        ]);
    }

    /**
     * Smеna hisobot
     */
    public function shifts() {
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        $shifts = $this->reportModel->getShiftReport($startDate, $endDate);
        $this->view('reports/shifts', [
            'shifts' => $shifts,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    /**
     * Qaytarishlar hisobot
     */
    public function returns() {
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        $returns = $this->reportModel->getReturnReport($startDate, $endDate);
        $this->view('reports/returns', [
            'returns' => $returns,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    
    /**
     * Excel export
     */
    public function exportExcel($type) {
        require_once 'vendor/autoload.php';

        $startDate = $_GET['start_date'] ?? date('Y-m-d');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $cashierId = $_GET['cashier_id'] ?? null;
        $dealerId = $_GET['dealer_id'] ?? null;

        $filters = [];
        if ($cashierId) $filters['cashier_id'] = $cashierId;
        if ($dealerId) $filters['dealer_id'] = $dealerId;

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        switch ($type) {
            case 'daily':
                $this->exportDailyExcel($spreadsheet, $startDate, $filters);
                $filename = 'daily_report_' . $startDate;
                break;

            case 'weekly':
                $this->exportWeeklyExcel($spreadsheet, $startDate, $endDate, $filters);
                $filename = 'weekly_report_' . $startDate . '_to_' . $endDate;
                break;

            case 'monthly':
                $this->exportMonthlyExcel($spreadsheet, $startDate, $endDate, $filters);
                $filename = 'monthly_report_' . $startDate . '_to_' . $endDate;
                break;

            default:
                // Eski export logikasi
                $this->exportLegacyExcel($spreadsheet, $type, $startDate, $endDate);
                $filename = $type . '_report_' . date('Y-m-d');
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    /**
     * Kunlik hisobot Excel export
     */
    private function exportDailyExcel($spreadsheet, $date, $filters) {
        $report = $this->reportModel->getDailyReport($date, $filters);

        // Summary sheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Summary');

        $sheet->setCellValue('A1', 'DAILY REPORT SUMMARY');
        $sheet->setCellValue('A2', 'Date: ' . $date);
        $sheet->setCellValue('A4', 'SALES SUMMARY');
        $sheet->setCellValue('A5', 'Gross Sales');
        $sheet->setCellValue('B5', $report['sales']['gross_sales']);
        $sheet->setCellValue('A6', 'Returns');
        $sheet->setCellValue('B6', $report['returns']['total_returns']);
        $sheet->setCellValue('A7', 'Net Sales');
        $sheet->setCellValue('B7', $report['sales']['gross_sales'] - $report['returns']['total_returns']);
        $sheet->setCellValue('A8', 'Cash Sales');
        $sheet->setCellValue('B8', $report['sales']['cash_sales']);
        $sheet->setCellValue('A9', 'Card Sales');
        $sheet->setCellValue('B9', $report['sales']['card_sales']);
        $sheet->setCellValue('A10', 'Mixed Sales');
        $sheet->setCellValue('B10', $report['sales']['mixed_sales']);

        $sheet->setCellValue('A12', 'DEBT COLLECTIONS');
        $sheet->setCellValue('A13', 'Total Collections');
        $sheet->setCellValue('B13', $report['debt_collections']['total_collections']);

        $sheet->setCellValue('A15', 'DEALER PAYMENTS');
        $sheet->setCellValue('A16', 'Total Payments');
        $sheet->setCellValue('B16', $report['dealer_payments']['total_payments']);

        $sheet->setCellValue('A18', 'CASH SUMMARY');
        $sheet->setCellValue('A19', 'Opening Cash');
        $sheet->setCellValue('B19', $report['cash']['opening_cash']);
        $sheet->setCellValue('A20', 'Cash Sales');
        $sheet->setCellValue('B20', $report['cash']['cash_sales']);
        $sheet->setCellValue('A21', 'Cash Debt Collections');
        $sheet->setCellValue('B21', $report['cash']['cash_debt_collections']);
        $sheet->setCellValue('A22', 'Cash Dealer Payments');
        $sheet->setCellValue('B22', $report['cash']['cash_dealer_payments']);
        $sheet->setCellValue('A23', 'Cash Refunds');
        $sheet->setCellValue('B23', $report['cash']['cash_refunds']);
        $sheet->setCellValue('A24', 'Expected Cash');
        $sheet->setCellValue('B24', $report['cash']['expected_cash']);
        if ($report['cash']['actual_cash'] !== null) {
            $sheet->setCellValue('A25', 'Actual Cash');
            $sheet->setCellValue('B25', $report['cash']['actual_cash']);
            $sheet->setCellValue('A26', 'Difference');
            $sheet->setCellValue('B26', $report['cash']['difference']);
        }

        // Sales details sheet
        $salesSheet = $spreadsheet->createSheet();
        $salesSheet->setTitle('Sales');
        $salesSheet->setCellValue('A1', 'Date');
        $salesSheet->setCellValue('B1', 'Receipt');
        $salesSheet->setCellValue('C1', 'Cashier');
        $salesSheet->setCellValue('D1', 'Customer');
        $salesSheet->setCellValue('E1', 'Payment Method');
        $salesSheet->setCellValue('F1', 'Amount');

        $sales = $this->reportModel->getDailySales($date);
        $row = 2;
        foreach ($sales as $sale) {
            $salesSheet->setCellValue('A'.$row, $sale['sotilgan_vaqt']);
            $salesSheet->setCellValue('B'.$row, $sale['chek_raqami']);
            $salesSheet->setCellValue('C'.$row, $sale['kassir_fio']);
            $salesSheet->setCellValue('D'.$row, $sale['mijoz_fio'] ?? 'Noma\'lum');
            $salesSheet->setCellValue('E'.$row, $sale['tolov_usuli']);
            $salesSheet->setCellValue('F'.$row, $sale['yakuniy_summa']);
            $row++;
        }

        // Dealer payments sheet
        $dealerSheet = $spreadsheet->createSheet();
        $dealerSheet->setTitle('Dealer Payments');
        $dealerSheet->setCellValue('A1', 'Dealer');
        $dealerSheet->setCellValue('B1', 'Phone');
        $dealerSheet->setCellValue('C1', 'Date');
        $dealerSheet->setCellValue('D1', 'Amount');
        $dealerSheet->setCellValue('E1', 'Method');
        $dealerSheet->setCellValue('F1', 'Note');

        $row = 2;
        foreach ($report['dealer_payments']['payments'] as $payment) {
            $dealerSheet->setCellValue('A'.$row, $payment['dealer_name']);
            $dealerSheet->setCellValue('B'.$row, $payment['dealer_phone']);
            $dealerSheet->setCellValue('C'.$row, $payment['sana']);
            $dealerSheet->setCellValue('D'.$row, $payment['summa']);
            $dealerSheet->setCellValue('E'.$row, $payment['payment_method']);
            $dealerSheet->setCellValue('F'.$row, $payment['izoh']);
            $row++;
        }

        // Debt collections sheet
        $debtSheet = $spreadsheet->createSheet();
        $debtSheet->setTitle('Debt Collections');
        $debtSheet->setCellValue('A1', 'Customer');
        $debtSheet->setCellValue('B1', 'Phone');
        $debtSheet->setCellValue('C1', 'Date');
        $debtSheet->setCellValue('D1', 'Amount');
        $debtSheet->setCellValue('E1', 'Method');
        $debtSheet->setCellValue('F1', 'Receipt');

        $row = 2;
        foreach ($report['debt_collections']['collections'] as $collection) {
            $debtSheet->setCellValue('A'.$row, $collection['customer_name']);
            $debtSheet->setCellValue('B'.$row, $collection['customer_phone']);
            $debtSheet->setCellValue('C'.$row, $collection['tolov_vaqt']);
            $debtSheet->setCellValue('D'.$row, $collection['summa']);
            $debtSheet->setCellValue('E'.$row, $collection['payment_method']);
            $debtSheet->setCellValue('F'.$row, $collection['sale_receipt']);
            $row++;
        }
    }

    /**
     * Haftalik hisobot Excel export
     */
    private function exportWeeklyExcel($spreadsheet, $startDate, $endDate, $filters) {
        $report = $this->reportModel->getWeeklyReport($startDate, $endDate, $filters);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Weekly Summary');

        $sheet->setCellValue('A1', 'WEEKLY REPORT SUMMARY');
        $sheet->setCellValue('A2', 'Period: ' . $startDate . ' - ' . $endDate);
        $sheet->setCellValue('A4', 'SALES SUMMARY');
        $sheet->setCellValue('A5', 'Gross Sales');
        $sheet->setCellValue('B5', $report['sales']['gross_sales']);
        $sheet->setCellValue('A6', 'Returns');
        $sheet->setCellValue('B6', $report['returns']['total_returns']);
        $sheet->setCellValue('A7', 'Net Sales');
        $sheet->setCellValue('B7', $report['sales']['gross_sales'] - $report['returns']['total_returns']);
        $sheet->setCellValue('A8', 'Cash Sales');
        $sheet->setCellValue('B8', $report['sales']['cash_sales']);
        $sheet->setCellValue('A9', 'Card Sales');
        $sheet->setCellValue('B9', $report['sales']['card_sales']);

        $sheet->setCellValue('A11', 'DEBT COLLECTIONS');
        $sheet->setCellValue('A12', 'Total Collections');
        $sheet->setCellValue('B12', $report['debt_collections']['total_collections']);

        $sheet->setCellValue('A14', 'DEALER PAYMENTS');
        $sheet->setCellValue('A15', 'Total Payments');
        $sheet->setCellValue('B15', $report['dealer_payments']['total_payments']);

        $sheet->setCellValue('A17', 'CASH SUMMARY');
        $sheet->setCellValue('A18', 'Expected Cash');
        $sheet->setCellValue('B18', $report['cash']['expected_cash']);
    }

    /**
     * Oylik hisobot Excel export
     */
    private function exportMonthlyExcel($spreadsheet, $startDate, $endDate, $filters) {
        $year = date('Y', strtotime($startDate));
        $month = date('m', strtotime($startDate));
        $report = $this->reportModel->getMonthlyReport($year, $month, $filters);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Monthly Summary');

        $monthNames = [
            1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
            5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
            9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr'
        ];

        $sheet->setCellValue('A1', 'MONTHLY REPORT SUMMARY');
        $sheet->setCellValue('A2', 'Period: ' . $monthNames[(int)$month] . ' ' . $year);
        $sheet->setCellValue('A4', 'SALES SUMMARY');
        $sheet->setCellValue('A5', 'Gross Sales');
        $sheet->setCellValue('B5', $report['sales']['gross_sales']);
        $sheet->setCellValue('A6', 'Returns');
        $sheet->setCellValue('B6', $report['returns']['total_returns']);
        $sheet->setCellValue('A7', 'Net Sales');
        $sheet->setCellValue('B7', $report['sales']['gross_sales'] - $report['returns']['total_returns']);
        $sheet->setCellValue('A8', 'Cash Sales');
        $sheet->setCellValue('B8', $report['sales']['cash_sales']);
        $sheet->setCellValue('A9', 'Card Sales');
        $sheet->setCellValue('B9', $report['sales']['card_sales']);

        $sheet->setCellValue('A11', 'DEBT COLLECTIONS');
        $sheet->setCellValue('A12', 'Total Collections');
        $sheet->setCellValue('B12', $report['debt_collections']['total_collections']);

        $sheet->setCellValue('A14', 'DEALER PAYMENTS');
        $sheet->setCellValue('A15', 'Total Payments');
        $sheet->setCellValue('B15', $report['dealer_payments']['total_payments']);

        $sheet->setCellValue('A17', 'CASH SUMMARY');
        $sheet->setCellValue('A18', 'Expected Cash');
        $sheet->setCellValue('B18', $report['cash']['expected_cash']);
    }

    /**
     * Eski export logikasi (saqlanib qolinadi)
     */
    private function exportLegacyExcel($spreadsheet, $type, $startDate, $endDate) {
        $sheet = $spreadsheet->getActiveSheet();

        switch ($type) {
            case 'products':
                $data = $this->reportModel->getTopProducts($startDate, $endDate, 50);
                $sheet->setTitle('Top mahsulotlar');
                $sheet->setCellValue('A1', 'Mahsulot');
                $sheet->setCellValue('B1', 'Sotilgan');
                $sheet->setCellValue('C1', 'Summa');
                $row = 2;
                foreach ($data as $product) {
                    $sheet->setCellValue('A'.$row, $product['nomi']);
                    $sheet->setCellValue('B'.$row, $product['sotilgan']);
                    $sheet->setCellValue('C'.$row, $product['summa']);
                    $row++;
                }
                break;

            case 'cashiers':
                $data = $this->reportModel->getCashierReport($startDate, $endDate);
                $sheet->setTitle('Kassirlar');
                $sheet->setCellValue('A1', 'Kassir');
                $sheet->setCellValue('B1', 'Savdolar');
                $sheet->setCellValue('C1', 'Jami savdo');
                $sheet->setCellValue('D1', 'O\'rtacha chek');
                $sheet->setCellValue('E1', 'Chegirma');
                $sheet->setCellValue('F1', 'Nasiya savdolar');
                $sheet->setCellValue('G1', 'Jami qarz');
                $row = 2;
                foreach ($data as $item) {
                    $sheet->setCellValue('A'.$row, $item['fio']);
                    $sheet->setCellValue('B'.$row, $item['savdolar_soni']);
                    $sheet->setCellValue('C'.$row, $item['jami_savdo']);
                    $sheet->setCellValue('D'.$row, $item['ortacha_chek']);
                    $sheet->setCellValue('E'.$row, $item['jami_chegirma']);
                    $sheet->setCellValue('F'.$row, $item['nasiya_soni']);
                    $sheet->setCellValue('G'.$row, $item['jami_qarz']);
                    $row++;
                }
                break;

            case 'debtors':
                $data = $this->reportModel->getDebtReport();
                $sheet->setTitle('Qarzdorlar');
                $sheet->setCellValue('A1', 'Mijoz');
                $sheet->setCellValue('B1', 'Telefon');
                $sheet->setCellValue('C1', 'Qarzli savdolar');
                $sheet->setCellValue('D1', 'Jami qarz');
                $sheet->setCellValue('E1', 'Oxirgi savdo');
                $sheet->setCellValue('F1', 'Kechikkan kun');
                $row = 2;
                foreach ($data as $item) {
                    $sheet->setCellValue('A'.$row, $item['fio']);
                    $sheet->setCellValue('B'.$row, $item['telefon']);
                    $sheet->setCellValue('C'.$row, $item['qarzli_savdolar']);
                    $sheet->setCellValue('D'.$row, $item['jami_qarz']);
                    $sheet->setCellValue('E'.$row, $item['oxirgi_savdo']);
                    $sheet->setCellValue('F'.$row, $item['kechikkan_kun']);
                    $row++;
                }
                break;

            case 'categories':
                $data = $this->reportModel->getCategoryReport($startDate, $endDate);
                $sheet->setTitle('Kategoriyalar');
                $sheet->setCellValue('A1', 'Kategoriya');
                $sheet->setCellValue('B1', 'Savdolar soni');
                $sheet->setCellValue('C1', 'Mahsulotlar soni');
                $sheet->setCellValue('D1', 'Jami soni');
                $sheet->setCellValue('E1', 'Jami summa');
                $row = 2;
                foreach ($data as $item) {
                    $sheet->setCellValue('A'.$row, $item['nomi']);
                    $sheet->setCellValue('B'.$row, $item['savdolar_soni']);
                    $sheet->setCellValue('C'.$row, $item['mahsulotlar_soni']);
                    $sheet->setCellValue('D'.$row, $item['jami_soni']);
                    $sheet->setCellValue('E'.$row, $item['jami_summa']);
                    $row++;
                }
                break;

            case 'dealers':
                $data = $this->reportModel->getDealerReport();
                $sheet->setTitle('Dillerlar');
                $sheet->setCellValue('A1', 'Diller');
                $sheet->setCellValue('B1', 'Telefon');
                $sheet->setCellValue('C1', 'Jami olingan');
                $sheet->setCellValue('D1', 'Jami to\'langan');
                $sheet->setCellValue('E1', 'Qarz');
                $sheet->setCellValue('F1', 'Oxirgi to\'lov');
                $sheet->setCellValue('G1', 'Oxirgi olish');
                $row = 2;
                foreach ($data as $item) {
                    $sheet->setCellValue('A'.$row, $item['nomi']);
                    $sheet->setCellValue('B'.$row, $item['telefon']);
                    $sheet->setCellValue('C'.$row, $item['jami_olingan']);
                    $sheet->setCellValue('D'.$row, $item['jami_tolangan']);
                    $sheet->setCellValue('E'.$row, $item['qarz']);
                    $sheet->setCellValue('F'.$row, $item['oxirgi_tolov_sana']);
                    $sheet->setCellValue('G'.$row, $item['oxirgi_olingan_sana']);
                    $row++;
                }
                break;

            case 'shifts':
                $data = $this->reportModel->getShiftReport($startDate, $endDate);
                $sheet->setTitle('Smеna');
                $sheet->setCellValue('A1', 'Kassir');
                $sheet->setCellValue('B1', 'Ochilish vaqti');
                $sheet->setCellValue('C1', 'Yopilish vaqti');
                $sheet->setCellValue('D1', 'Boshlang\'ich naqd');
                $sheet->setCellValue('E1', 'Naqd tushum');
                $sheet->setCellValue('F1', 'Qaytarish');
                $sheet->setCellValue('G1', 'Diller to\'lovlari');
                $sheet->setCellValue('H1', 'Kutilgan naqd');
                $row = 2;
                foreach ($data as $item) {
                    $sheet->setCellValue('A'.$row, $item['kassir_fio']);
                    $sheet->setCellValue('B'.$row, $item['ochilgan_vaqt']);
                    $sheet->setCellValue('C'.$row, ($item['yopilgan_vaqt'] ?? ''));
                    $sheet->setCellValue('D'.$row, $item['ochilish_naqd']);
                    $sheet->setCellValue('E'.$row, $item['jami_naqd_tolov']);
                    $sheet->setCellValue('F'.$row, $item['qaytarilgan_summa']);
                    $sheet->setCellValue('G'.$row, $item['diller_tolovlari']);
                    $sheet->setCellValue('H'.$row, $item['expected_cash']);
                    $row++;
                }
                break;

            case 'returns':
                $data = $this->reportModel->getReturnReport($startDate, $endDate);
                $sheet->setTitle('Qaytarishlar');
                $sheet->setCellValue('A1', 'Chek');
                $sheet->setCellValue('B1', 'Kassir');
                $sheet->setCellValue('C1', 'Mijoz');
                $sheet->setCellValue('D1', 'Mahsulot');
                $sheet->setCellValue('E1', 'Miqdor');
                $sheet->setCellValue('F1', 'Summa');
                $sheet->setCellValue('G1', 'Sabab');
                $sheet->setCellValue('H1', 'Sana');
                $row = 2;
                foreach ($data as $item) {
                    $sheet->setCellValue('A'.$row, $item['chek_raqami']);
                    $sheet->setCellValue('B'.$row, $item['kassir_fio']);
                    $sheet->setCellValue('C'.$row, $item['mijoz_fio']);
                    $sheet->setCellValue('D'.$row, $item['mahsulot_nomi']);
                    $sheet->setCellValue('E'.$row, $item['miqdor']);
                    $sheet->setCellValue('F'.$row, $item['summa']);
                    $sheet->setCellValue('G'.$row, $item['sabab']);
                    $sheet->setCellValue('H'.$row, $item['qaytarilgan_vaqt']);
                    $row++;
                }
                break;
        }
    }
    
    /**
     * PDF export
     */
    public function exportPdf($type) {
        require_once 'vendor/autoload.php';
        
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $html = '<!DOCTYPE html><html lang="uz"><head><meta charset="UTF-8"><title>' . ucfirst($type) . ' Hisoboti</title>';
        $html .= '<style>body{font-family: DejaVu Sans, Arial, sans-serif; font-size:12px;}.report-table{width:100%;border-collapse:collapse;margin-top:15px;} .report-table th, .report-table td{border:1px solid #ccc;padding:6px;text-align:left;} .report-title{margin:0; padding:0;}</style>';
        $html .= '</head><body>';
        $html .= '<h1 class="report-title">' . ucfirst($type) . ' Hisoboti</h1>';
        $html .= '<p>Sana: ' . $startDate . ' - ' . $endDate . '</p>';
        
        switch ($type) {
            case 'daily':
                $data = $this->reportModel->getDailySales($startDate);
                $html .= '<table class="report-table">';
                $html .= '<tr><th>Sana</th><th>Chek</th><th>Kassir</th><th>Summa</th></tr>';
                foreach ($data as $sale) {
                    $html .= '<tr>';
                    $html .= '<td>' . $sale['sotilgan_vaqt'] . '</td>';
                    $html .= '<td>' . $sale['chek_raqami'] . '</td>';
                    $html .= '<td>' . $sale['kassir_fio'] . '</td>';
                    $html .= '<td>' . number_format($sale['yakuniy_summa'], 2) . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                break;

            case 'cashiers':
                $data = $this->reportModel->getCashierReport($startDate, $endDate);
                $html .= '<table class="report-table">';
                $html .= '<tr><th>Kassir</th><th>Savdolar</th><th>Jami savdo</th><th>O\'rtacha chek</th><th>Chegirma</th><th>Nasiya savdo</th><th>Jami qarz</th></tr>';
                foreach ($data as $item) {
                    $html .= '<tr>';
                    $html .= '<td>' . $item['fio'] . '</td>';
                    $html .= '<td>' . $item['savdolar_soni'] . '</td>';
                    $html .= '<td>' . $item['jami_savdo'] . '</td>';
                    $html .= '<td>' . $item['ortacha_chek'] . '</td>';
                    $html .= '<td>' . $item['jami_chegirma'] . '</td>';
                    $html .= '<td>' . $item['nasiya_soni'] . '</td>';
                    $html .= '<td>' . $item['jami_qarz'] . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                break;

            case 'debtors':
                $data = $this->reportModel->getDebtReport();
                $html .= '<table class="report-table">';
                $html .= '<tr><th>Mijoz</th><th>Telefon</th><th>Qarzli savdolar</th><th>Jami qarz</th><th>Oxirgi savdo</th><th>Kechikkan kun</th></tr>';
                foreach ($data as $item) {
                    $html .= '<tr>';
                    $html .= '<td>' . $item['fio'] . '</td>';
                    $html .= '<td>' . $item['telefon'] . '</td>';
                    $html .= '<td>' . $item['qarzli_savdolar'] . '</td>';
                    $html .= '<td>' . $item['jami_qarz'] . '</td>';
                    $html .= '<td>' . $item['oxirgi_savdo'] . '</td>';
                    $html .= '<td>' . $item['kechikkan_kun'] . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                break;

            case 'categories':
                $data = $this->reportModel->getCategoryReport($startDate, $endDate);
                $html .= '<table class="report-table">';
                $html .= '<tr><th>Kategoriya</th><th>Savdolar soni</th><th>Mahsulotlar soni</th><th>Jami soni</th><th>Jami summa</th></tr>';
                foreach ($data as $item) {
                    $html .= '<tr>';
                    $html .= '<td>' . $item['nomi'] . '</td>';
                    $html .= '<td>' . $item['savdolar_soni'] . '</td>';
                    $html .= '<td>' . $item['mahsulotlar_soni'] . '</td>';
                    $html .= '<td>' . $item['jami_soni'] . '</td>';
                    $html .= '<td>' . $item['jami_summa'] . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                break;

            case 'dealers':
                $data = $this->reportModel->getDealerReport();
                $html .= '<table class="report-table">';
                $html .= '<tr><th>Diller</th><th>Telefon</th><th>Jami olingan</th><th>Jami to\'langan</th><th>Qarz</th><th>Oxirgi to\'lov</th><th>Oxirgi olish</th></tr>';
                foreach ($data as $item) {
                    $html .= '<tr>';
                    $html .= '<td>' . $item['nomi'] . '</td>';
                    $html .= '<td>' . $item['telefon'] . '</td>';
                    $html .= '<td>' . $item['jami_olingan'] . '</td>';
                    $html .= '<td>' . $item['jami_tolangan'] . '</td>';
                    $html .= '<td>' . $item['qarz'] . '</td>';
                    $html .= '<td>' . ($item['oxirgi_tolov_sana'] ?? '') . '</td>';
                    $html .= '<td>' . ($item['oxirgi_olingan_sana'] ?? '') . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                break;

            case 'shifts':
                $data = $this->reportModel->getShiftReport($startDate, $endDate);
                $html .= '<table class="report-table">';
                $html .= '<tr><th>Kassir</th><th>Ochilish</th><th>Yopilish</th><th>Boshlang\'ich naqd</th><th>Naqd tushum</th><th>Qaytarish</th><th>Diller to\'lovlari</th><th>Kutilgan naqd</th></tr>';
                foreach ($data as $item) {
                    $html .= '<tr>';
                    $html .= '<td>' . $item['kassir_fio'] . '</td>';
                    $html .= '<td>' . $item['ochilgan_vaqt'] . '</td>';
                    $html .= '<td>' . ($item['yopilgan_vaqt'] ?? '') . '</td>';
                    $html .= '<td>' . $item['ochilish_naqd'] . '</td>';
                    $html .= '<td>' . $item['jami_naqd_tolov'] . '</td>';
                    $html .= '<td>' . $item['qaytarilgan_summa'] . '</td>';
                    $html .= '<td>' . $item['diller_tolovlari'] . '</td>';
                    $html .= '<td>' . $item['expected_cash'] . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                break;

            case 'returns':
                $data = $this->reportModel->getReturnReport($startDate, $endDate);
                $html .= '<table class="report-table">';
                $html .= '<tr><th>Chek</th><th>Kassir</th><th>Mijoz</th><th>Mahsulot</th><th>Miqdor</th><th>Summa</th><th>Sabab</th><th>Sana</th></tr>';
                foreach ($data as $item) {
                    $html .= '<tr>';
                    $html .= '<td>' . $item['chek_raqami'] . '</td>';
                    $html .= '<td>' . $item['kassir_fio'] . '</td>';
                    $html .= '<td>' . $item['mijoz_fio'] . '</td>';
                    $html .= '<td>' . $item['mahsulot_nomi'] . '</td>';
                    $html .= '<td>' . $item['miqdor'] . '</td>';
                    $html .= '<td>' . $item['summa'] . '</td>';
                    $html .= '<td>' . $item['sabab'] . '</td>';
                    $html .= '<td>' . $item['qaytarilgan_vaqt'] . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                break;
        }
        
        $html .= '</body></html>';

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($type . '_report_' . date('Y-m-d') . '.pdf');
        exit;
    }

    /**
     * Kunlik hisobot PDF export
     */
    public function exportDailyPdf() {
        require_once 'vendor/autoload.php';

        $date = $_GET['date'] ?? date('Y-m-d');
        $cashierId = $_GET['cashier_id'] ?? null;
        $dealerId = $_GET['dealer_id'] ?? null;

        $filters = [];
        if ($cashierId) $filters['cashier_id'] = $cashierId;
        if ($dealerId) $filters['dealer_id'] = $dealerId;

        $report = $this->reportModel->getDailyReport($date, $filters);

        $html = $this->generateDailyReportHtml($report, $date, $filters, 'pdf');

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('daily_report_' . $date . '.pdf');
        exit;
    }

    /**
     * Haftalik hisobot PDF export
     */
    public function exportWeeklyPdf() {
        require_once 'vendor/autoload.php';

        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('monday this week'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d', strtotime('sunday this week'));
        $cashierId = $_GET['cashier_id'] ?? null;
        $dealerId = $_GET['dealer_id'] ?? null;

        $filters = [];
        if ($cashierId) $filters['cashier_id'] = $cashierId;
        if ($dealerId) $filters['dealer_id'] = $dealerId;

        $report = $this->reportModel->getWeeklyReport($startDate, $endDate, $filters);

        $html = $this->generateWeeklyReportHtml($report, $startDate, $endDate, $filters, 'pdf');

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('weekly_report_' . $startDate . '_to_' . $endDate . '.pdf');
        exit;
    }

    /**
     * Oylik hisobot PDF export
     */
    public function exportMonthlyPdf() {
        require_once 'vendor/autoload.php';

        $month = $_GET['month'] ?? date('Y-m');
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));
        $cashierId = $_GET['cashier_id'] ?? null;
        $dealerId = $_GET['dealer_id'] ?? null;

        $filters = [];
        if ($cashierId) $filters['cashier_id'] = $cashierId;
        if ($dealerId) $filters['dealer_id'] = $dealerId;

        $year = date('Y', strtotime($startDate));
        $monthNum = date('m', strtotime($startDate));
        $report = $this->reportModel->getMonthlyReport($year, $monthNum, $filters);

        $html = $this->generateMonthlyReportHtml($report, $startDate, $endDate, $filters, 'pdf');

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('monthly_report_' . $month . '.pdf');
        exit;
    }

    /**
     * Print sahifalari
     */
    public function printReport($type) {
        $date = $_GET['date'] ?? date('Y-m-d');
        $startDate = $_GET['start_date'] ?? date('Y-m-d');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $cashierId = $_GET['cashier_id'] ?? null;
        $dealerId = $_GET['dealer_id'] ?? null;

        $filters = [];
        if ($cashierId) $filters['cashier_id'] = $cashierId;
        if ($dealerId) $filters['dealer_id'] = $dealerId;

        switch ($type) {
            case 'daily':
                $report = $this->reportModel->getDailyReport($date, $filters);
                $html = $this->generateDailyReportHtml($report, $date, $filters, 'print');
                break;

            case 'weekly':
                $report = $this->reportModel->getWeeklyReport($startDate, $endDate, $filters);
                $html = $this->generateWeeklyReportHtml($report, $startDate, $endDate, $filters, 'print');
                break;

            case 'monthly':
                $year = date('Y', strtotime($startDate));
                $month = date('m', strtotime($startDate));
                $report = $this->reportModel->getMonthlyReport($year, $month, $filters);
                $html = $this->generateMonthlyReportHtml($report, $startDate, $endDate, $filters, 'print');
                break;

            default:
                $html = '<h1>Noma\'lum hisobot turi</h1>';
        }

        // Print uchun minimal styling
        $printHtml = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Print</title>';
        $printHtml .= '<style>@media print { body { font-size: 12px; } .no-print { display: none; } }</style>';
        $printHtml .= '</head><body>';
        $printHtml .= $html;
        $printHtml .= '<script>window.onload = function() { window.print(); }</script>';
        $printHtml .= '</body></html>';

        echo $printHtml;
        exit;
    }

    /**
     * Kunlik hisobot HTML generatsiya
     */
    private function generateDailyReportHtml($report, $date, $filters, $output = 'pdf') {
        $html = '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;">';

        // Sarlavha
        $html .= '<h1 style="text-align: center; color: #333; margin-bottom: 10px;">DAILY REPORT</h1>';
        $html .= '<h2 style="text-align: center; color: #666; margin-bottom: 30px;">' . date('d.m.Y', strtotime($date)) . '</h2>';

        // Summary cards
        $html .= '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 30px;">';

        $cards = [
            ['title' => 'Gross Sales', 'value' => number_format($report['sales']['gross_sales'], 0, ',', ' ') . ' so\'m', 'color' => '#667eea'],
            ['title' => 'Returns', 'value' => number_format($report['returns']['total_returns'], 0, ',', ' ') . ' so\'m', 'color' => '#ffc107'],
            ['title' => 'Net Sales', 'value' => number_format($report['sales']['gross_sales'] - $report['returns']['total_returns'], 0, ',', ' ') . ' so\'m', 'color' => '#28a745'],
            ['title' => 'Cash Sales', 'value' => number_format($report['sales']['cash_sales'], 0, ',', ' ') . ' so\'m', 'color' => '#17a2b8'],
        ];

        foreach ($cards as $card) {
            $html .= '<div style="background: white; border-radius: 8px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid ' . $card['color'] . '; text-align: center;">';
            $html .= '<div style="font-size: 18px; font-weight: bold; color: #333;">' . $card['value'] . '</div>';
            $html .= '<div style="color: #666; font-size: 12px;">' . $card['title'] . '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';

        // Cash Summary
        $html .= '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; padding: 20px; margin-bottom: 30px;">';
        $html .= '<h3 style="margin: 0 0 15px 0; font-size: 16px;">Kassa hisob-kitobi</h3>';

        $cashRows = [
            ['label' => 'Boshlang\'ich kassa', 'value' => number_format($report['cash']['opening_cash'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Naqd savdo', 'value' => '+ ' . number_format($report['cash']['cash_sales'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Qarz to\'lovlari (naqd)', 'value' => '+ ' . number_format($report['cash']['cash_debt_collections'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Diller to\'lovlari (naqd)', 'value' => '- ' . number_format($report['cash']['cash_dealer_payments'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Qaytarishlar (naqd)', 'value' => '- ' . number_format($report['cash']['cash_refunds'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Kutilgan kassa', 'value' => number_format($report['cash']['expected_cash'], 0, ',', ' ') . ' so\'m', 'highlight' => true],
        ];

        if ($report['cash']['actual_cash'] !== null) {
            $cashRows[] = ['label' => 'Haqiqiy kassa', 'value' => number_format($report['cash']['actual_cash'], 0, ',', ' ') . ' so\'m'];
            $cashRows[] = ['label' => 'Farq (' . ($report['cash']['difference'] >= 0 ? 'ortiqcha' : 'kamomad') . ')', 'value' => number_format(abs($report['cash']['difference']), 0, ',', ' ') . ' so\'m', 'highlight' => true];
        }

        foreach ($cashRows as $row) {
            $style = isset($row['highlight']) ? 'font-weight: bold; font-size: 14px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.3);' : '';
            $html .= '<div style="display: flex; justify-content: space-between; padding: 5px 0; ' . $style . '">';
            $html .= '<span>' . $row['label'] . '</span>';
            $html .= '<span>' . $row['value'] . '</span>';
            $html .= '</div>';
        }

        $html .= '</div>';

        // Tables
        $tables = [
            [
                'title' => 'Diller to\'lovlari',
                'data' => $report['dealer_payments']['payments'],
                'headers' => ['Diller', 'Telefon', 'Sana', 'Summa'],
                'fields' => ['dealer_name', 'dealer_phone', 'sana', 'summa']
            ],
            [
                'title' => 'Qarz to\'lovlari',
                'data' => $report['debt_collections']['collections'],
                'headers' => ['Mijoz', 'Telefon', 'Sana', 'Summa'],
                'fields' => ['customer_name', 'customer_phone', 'tolov_vaqt', 'summa']
            ],
            [
                'title' => 'Qaytarishlar',
                'data' => $report['returns']['returns'],
                'headers' => ['Sana', 'Chek', 'Mahsulot', 'Miqdor', 'Summa'],
                'fields' => ['qaytarilgan_vaqt', 'chek_raqami', 'product_name', 'miqdor', 'summa']
            ]
        ];

        foreach ($tables as $table) {
            if (!empty($table['data'])) {
                $html .= '<h3 style="color: #333; margin: 30px 0 15px 0; font-size: 16px;">' . $table['title'] . '</h3>';
                $html .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">';
                $html .= '<thead><tr style="background: #f8f9fa;">';
                foreach ($table['headers'] as $header) {
                    $html .= '<th style="border: 1px solid #ddd; padding: 8px; text-align: left; font-weight: bold;">' . $header . '</th>';
                }
                $html .= '</tr></thead><tbody>';

                foreach ($table['data'] as $row) {
                    $html .= '<tr>';
                    foreach ($table['fields'] as $field) {
                        $value = $row[$field] ?? '';
                        if (in_array($field, ['summa', 'miqdor'])) {
                            $value = number_format($value, 0, ',', ' ');
                        }
                        if (in_array($field, ['sana', 'tolov_vaqt', 'qaytarilgan_vaqt'])) {
                            $value = date('d.m.Y H:i', strtotime($value));
                        }
                        $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($value) . '</td>';
                    }
                    $html .= '</tr>';
                }

                $html .= '</tbody></table>';
            }
        }

        $html .= '</div>';
        return $html;
    }

    /**
     * Haftalik hisobot HTML generatsiya
     */
    private function generateWeeklyReportHtml($report, $startDate, $endDate, $filters, $output = 'pdf') {
        $html = '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;">';

        // Sarlavha
        $html .= '<h1 style="text-align: center; color: #333; margin-bottom: 10px;">WEEKLY REPORT</h1>';
        $html .= '<h2 style="text-align: center; color: #666; margin-bottom: 30px;">' . date('d.m.Y', strtotime($startDate)) . ' - ' . date('d.m.Y', strtotime($endDate)) . '</h2>';

        // Summary cards (similar to daily)
        $html .= '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 30px;">';

        $cards = [
            ['title' => 'Gross Sales', 'value' => number_format($report['sales']['gross_sales'], 0, ',', ' ') . ' so\'m', 'color' => '#667eea'],
            ['title' => 'Returns', 'value' => number_format($report['returns']['total_returns'], 0, ',', ' ') . ' so\'m', 'color' => '#ffc107'],
            ['title' => 'Net Sales', 'value' => number_format($report['sales']['gross_sales'] - $report['returns']['total_returns'], 0, ',', ' ') . ' so\'m', 'color' => '#28a745'],
            ['title' => 'Cash Sales', 'value' => number_format($report['sales']['cash_sales'], 0, ',', ' ') . ' so\'m', 'color' => '#17a2b8'],
        ];

        foreach ($cards as $card) {
            $html .= '<div style="background: white; border-radius: 8px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid ' . $card['color'] . '; text-align: center;">';
            $html .= '<div style="font-size: 18px; font-weight: bold; color: #333;">' . $card['value'] . '</div>';
            $html .= '<div style="color: #666; font-size: 12px;">' . $card['title'] . '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';

        // Cash Summary (same as daily)
        $html .= '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; padding: 20px; margin-bottom: 30px;">';
        $html .= '<h3 style="margin: 0 0 15px 0; font-size: 16px;">Kassa hisob-kitobi</h3>';

        $cashRows = [
            ['label' => 'Boshlang\'ich kassa', 'value' => number_format($report['cash']['opening_cash'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Naqd savdo', 'value' => '+ ' . number_format($report['cash']['cash_sales'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Qarz to\'lovlari (naqd)', 'value' => '+ ' . number_format($report['cash']['cash_debt_collections'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Diller to\'lovlari (naqd)', 'value' => '- ' . number_format($report['cash']['cash_dealer_payments'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Qaytarishlar (naqd)', 'value' => '- ' . number_format($report['cash']['cash_refunds'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Kutilgan kassa', 'value' => number_format($report['cash']['expected_cash'], 0, ',', ' ') . ' so\'m', 'highlight' => true],
        ];

        if ($report['cash']['actual_cash'] !== null) {
            $cashRows[] = ['label' => 'Haqiqiy kassa', 'value' => number_format($report['cash']['actual_cash'], 0, ',', ' ') . ' so\'m'];
            $cashRows[] = ['label' => 'Farq (' . ($report['cash']['difference'] >= 0 ? 'ortiqcha' : 'kamomad') . ')', 'value' => number_format(abs($report['cash']['difference']), 0, ',', ' ') . ' so\'m', 'highlight' => true];
        }

        foreach ($cashRows as $row) {
            $style = isset($row['highlight']) ? 'font-weight: bold; font-size: 14px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.3);' : '';
            $html .= '<div style="display: flex; justify-content: space-between; padding: 5px 0; ' . $style . '">';
            $html .= '<span>' . $row['label'] . '</span>';
            $html .= '<span>' . $row['value'] . '</span>';
            $html .= '</div>';
        }

        $html .= '</div>';

        // Weekly breakdown
        if (!empty($report['weekly_breakdown'])) {
            $html .= '<h3 style="color: #333; margin: 30px 0 15px 0; font-size: 16px;">Haftalik tafsilotlar</h3>';
            foreach ($report['weekly_breakdown'] as $week => $weekData) {
                $html .= '<div style="background: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 15px;">';
                $html .= '<h4 style="margin: 0 0 10px 0; color: #333;">' . $week . '. hafta</h4>';
                $html .= '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">';
                $html .= '<div><strong>Savdo:</strong> ' . number_format($weekData['gross_sales'], 0, ',', ' ') . ' so\'m</div>';
                $html .= '<div><strong>Qaytarish:</strong> ' . number_format($weekData['returns'], 0, ',', ' ') . ' so\'m</div>';
                $html .= '<div><strong>Qarz to\'lovlari:</strong> ' . number_format($weekData['debt_collections'], 0, ',', ' ') . ' so\'m</div>';
                $html .= '<div><strong>Diller to\'lovlari:</strong> ' . number_format($weekData['dealer_payments'], 0, ',', ' ') . ' so\'m</div>';
                $html .= '</div></div>';
            }
        }

        // Tables (same structure as daily)
        $tables = [
            [
                'title' => 'Diller to\'lovlari',
                'data' => $report['dealer_payments']['payments'],
                'headers' => ['Diller', 'Telefon', 'Sana', 'Summa'],
                'fields' => ['dealer_name', 'dealer_phone', 'sana', 'summa']
            ],
            [
                'title' => 'Qarz to\'lovlari',
                'data' => $report['debt_collections']['collections'],
                'headers' => ['Mijoz', 'Telefon', 'Sana', 'Summa'],
                'fields' => ['customer_name', 'customer_phone', 'tolov_vaqt', 'summa']
            ],
            [
                'title' => 'Qaytarishlar',
                'data' => $report['returns']['returns'],
                'headers' => ['Sana', 'Chek', 'Mahsulot', 'Miqdor', 'Summa'],
                'fields' => ['qaytarilgan_vaqt', 'chek_raqami', 'product_name', 'miqdor', 'summa']
            ]
        ];

        foreach ($tables as $table) {
            if (!empty($table['data'])) {
                $html .= '<h3 style="color: #333; margin: 30px 0 15px 0; font-size: 16px;">' . $table['title'] . '</h3>';
                $html .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">';
                $html .= '<thead><tr style="background: #f8f9fa;">';
                foreach ($table['headers'] as $header) {
                    $html .= '<th style="border: 1px solid #ddd; padding: 8px; text-align: left; font-weight: bold;">' . $header . '</th>';
                }
                $html .= '</tr></thead><tbody>';

                foreach ($table['data'] as $row) {
                    $html .= '<tr>';
                    foreach ($table['fields'] as $field) {
                        $value = $row[$field] ?? '';
                        if (in_array($field, ['summa', 'miqdor'])) {
                            $value = number_format($value, 0, ',', ' ');
                        }
                        if (in_array($field, ['sana', 'tolov_vaqt', 'qaytarilgan_vaqt'])) {
                            $value = date('d.m.Y H:i', strtotime($value));
                        }
                        $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($value) . '</td>';
                    }
                    $html .= '</tr>';
                }

                $html .= '</tbody></table>';
            }
        }

        $html .= '</div>';
        return $html;
    }

    /**
     * Oylik hisobot HTML generatsiya
     */
    private function generateMonthlyReportHtml($report, $startDate, $endDate, $filters, $output = 'pdf') {
        $monthNames = [
            1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
            5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
            9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr'
        ];
        $monthNum = date('n', strtotime($startDate));
        $year = date('Y', strtotime($startDate));

        $html = '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;">';

        // Sarlavha
        $html .= '<h1 style="text-align: center; color: #333; margin-bottom: 10px;">MONTHLY REPORT</h1>';
        $html .= '<h2 style="text-align: center; color: #666; margin-bottom: 30px;">' . $monthNames[$monthNum] . ' ' . $year . '</h2>';

        // Summary cards
        $html .= '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 30px;">';

        $cards = [
            ['title' => 'Gross Sales', 'value' => number_format($report['sales']['gross_sales'], 0, ',', ' ') . ' so\'m', 'color' => '#667eea'],
            ['title' => 'Returns', 'value' => number_format($report['returns']['total_returns'], 0, ',', ' ') . ' so\'m', 'color' => '#ffc107'],
            ['title' => 'Net Sales', 'value' => number_format($report['sales']['gross_sales'] - $report['returns']['total_returns'], 0, ',', ' ') . ' so\'m', 'color' => '#28a745'],
            ['title' => 'Cash Sales', 'value' => number_format($report['sales']['cash_sales'], 0, ',', ' ') . ' so\'m', 'color' => '#17a2b8'],
        ];

        foreach ($cards as $card) {
            $html .= '<div style="background: white; border-radius: 8px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid ' . $card['color'] . '; text-align: center;">';
            $html .= '<div style="font-size: 18px; font-weight: bold; color: #333;">' . $card['value'] . '</div>';
            $html .= '<div style="color: #666; font-size: 12px;">' . $card['title'] . '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';

        // Cash Summary
        $html .= '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; padding: 20px; margin-bottom: 30px;">';
        $html .= '<h3 style="margin: 0 0 15px 0; font-size: 16px;">Kassa hisob-kitobi</h3>';

        $cashRows = [
            ['label' => 'Boshlang\'ich kassa', 'value' => number_format($report['cash']['opening_cash'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Naqd savdo', 'value' => '+ ' . number_format($report['cash']['cash_sales'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Qarz to\'lovlari (naqd)', 'value' => '+ ' . number_format($report['cash']['cash_debt_collections'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Diller to\'lovlari (naqd)', 'value' => '- ' . number_format($report['cash']['cash_dealer_payments'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Qaytarishlar (naqd)', 'value' => '- ' . number_format($report['cash']['cash_refunds'], 0, ',', ' ') . ' so\'m'],
            ['label' => 'Kutilgan kassa', 'value' => number_format($report['cash']['expected_cash'], 0, ',', ' ') . ' so\'m', 'highlight' => true],
        ];

        if ($report['cash']['actual_cash'] !== null) {
            $cashRows[] = ['label' => 'Haqiqiy kassa', 'value' => number_format($report['cash']['actual_cash'], 0, ',', ' ') . ' so\'m'];
            $cashRows[] = ['label' => 'Farq (' . ($report['cash']['difference'] >= 0 ? 'ortiqcha' : 'kamomad') . ')', 'value' => number_format(abs($report['cash']['difference']), 0, ',', ' ') . ' so\'m', 'highlight' => true];
        }

        foreach ($cashRows as $row) {
            $style = isset($row['highlight']) ? 'font-weight: bold; font-size: 14px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.3);' : '';
            $html .= '<div style="display: flex; justify-content: space-between; padding: 5px 0; ' . $style . '">';
            $html .= '<span>' . $row['label'] . '</span>';
            $html .= '<span>' . $row['value'] . '</span>';
            $html .= '</div>';
        }

        $html .= '</div>';

        // Weekly breakdown
        if (!empty($report['weekly_breakdown'])) {
            $html .= '<h3 style="color: #333; margin: 30px 0 15px 0; font-size: 16px;">Haftalik tafsilotlar</h3>';
            foreach ($report['weekly_breakdown'] as $week => $weekData) {
                $html .= '<div style="background: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 15px;">';
                $html .= '<h4 style="margin: 0 0 10px 0; color: #333;">' . $week . '. hafta</h4>';
                $html .= '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">';
                $html .= '<div><strong>Savdo:</strong> ' . number_format($weekData['gross_sales'], 0, ',', ' ') . ' so\'m</div>';
                $html .= '<div><strong>Qaytarish:</strong> ' . number_format($weekData['returns'], 0, ',', ' ') . ' so\'m</div>';
                $html .= '<div><strong>Qarz to\'lovlari:</strong> ' . number_format($weekData['debt_collections'], 0, ',', ' ') . ' so\'m</div>';
                $html .= '<div><strong>Diller to\'lovlari:</strong> ' . number_format($weekData['dealer_payments'], 0, ',', ' ') . ' so\'m</div>';
                $html .= '</div></div>';
            }
        }

        // Tables (same as weekly)
        $tables = [
            [
                'title' => 'Diller to\'lovlari',
                'data' => $report['dealer_payments']['payments'],
                'headers' => ['Diller', 'Telefon', 'Sana', 'Summa'],
                'fields' => ['dealer_name', 'dealer_phone', 'sana', 'summa']
            ],
            [
                'title' => 'Qarz to\'lovlari',
                'data' => $report['debt_collections']['collections'],
                'headers' => ['Mijoz', 'Telefon', 'Sana', 'Summa'],
                'fields' => ['customer_name', 'customer_phone', 'tolov_vaqt', 'summa']
            ],
            [
                'title' => 'Qaytarishlar',
                'data' => $report['returns']['returns'],
                'headers' => ['Sana', 'Chek', 'Mahsulot', 'Miqdor', 'Summa'],
                'fields' => ['qaytarilgan_vaqt', 'chek_raqami', 'product_name', 'miqdor', 'summa']
            ]
        ];

        foreach ($tables as $table) {
            if (!empty($table['data'])) {
                $html .= '<h3 style="color: #333; margin: 30px 0 15px 0; font-size: 16px;">' . $table['title'] . '</h3>';
                $html .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">';
                $html .= '<thead><tr style="background: #f8f9fa;">';
                foreach ($table['headers'] as $header) {
                    $html .= '<th style="border: 1px solid #ddd; padding: 8px; text-align: left; font-weight: bold;">' . $header . '</th>';
                }
                $html .= '</tr></thead><tbody>';

                foreach ($table['data'] as $row) {
                    $html .= '<tr>';
                    foreach ($table['fields'] as $field) {
                        $value = $row[$field] ?? '';
                        if (in_array($field, ['summa', 'miqdor'])) {
                            $value = number_format($value, 0, ',', ' ');
                        }
                        if (in_array($field, ['sana', 'tolov_vaqt', 'qaytarilgan_vaqt'])) {
                            $value = date('d.m.Y H:i', strtotime($value));
                        }
                        $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($value) . '</td>';
                    }
                    $html .= '</tr>';
                }

                $html .= '</tbody></table>';
            }
        }

        $html .= '</div>';
        return $html;
    }
}