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
        
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        switch ($type) {
            case 'daily':
                $data = $this->reportModel->getDailySales($startDate);
                $sheet->setTitle('Kunlik savdolar');
                $sheet->setCellValue('A1', 'Sana');
                $sheet->setCellValue('B1', 'Chek raqami');
                $sheet->setCellValue('C1', 'Kassir');
                $sheet->setCellValue('D1', 'Summa');
                $row = 2;
                foreach ($data as $sale) {
                    $sheet->setCellValue('A'.$row, $sale['sotilgan_vaqt']);
                    $sheet->setCellValue('B'.$row, $sale['chek_raqami']);
                    $sheet->setCellValue('C'.$row, $sale['kassir_fio']);
                    $sheet->setCellValue('D'.$row, $sale['yakuniy_summa']);
                    $row++;
                }
                break;
                
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
                $sheet->setCellValue('D1', 'Jami to‘langan');
                $sheet->setCellValue('E1', 'Qarz');
                $sheet->setCellValue('F1', 'Oxirgi to‘lov');
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
                $sheet->setCellValue('G1', 'Diller to‘lovlari');
                $sheet->setCellValue('H1', 'Kutilgan naqd');
                $row = 2;
                foreach ($data as $item) {
                    $sheet->setCellValue('A'.$row, $item['kassir_fio']);
                    $sheet->setCellValue('B'.$row, $item['ochilgan_vaqt']);
                    $sheet->setCellValue('C'.$row, $item['yopilgan_vaqt']);
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
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $type . '_report_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
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
    
}