<?php
// app/Controllers/DebtController.php

namespace App\Controllers;

use App\Models\Debt;
use App\Models\Customer;

class DebtController extends Controller {
    
    private $debtModel;
    private $customerModel;
    
    public function __construct() {
        parent::__construct();
        $this->debtModel = new Debt();
        $this->customerModel = new Customer();
    }
    
    /**
     * Qarzdorlar ro'yxati
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $debtors = $this->debtModel->getAllDebtors();
        $overdue = $this->debtModel->getOverdueDebts();
        
        $this->view('debt/index', [
            'debtors' => $debtors,
            'overdue' => $overdue
        ]);
    }
    
    /**
     * Mijoz qarzlari
     */
    public function customer($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            $_SESSION['flash']['error'] = 'Mijoz topilmadi';
            $this->redirect('debt');
        }
        
        $debtInfo = $this->debtModel->getCustomerDebt($id);
        $history = $this->debtModel->getCustomerDebtHistory($id);
        $payments = $this->debtModel->getCustomerPayments($id);
        
        $this->view('debt/customer', [
            'customer' => $customer,
            'debtInfo' => $debtInfo,
            'history' => $history,
            'payments' => $payments
        ]);
    }
    
    /**
     * To'lov qabul qilish formasi
     */
    public function payment($savdoId) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        // Savdo ma'lumotlarini olish
        $stmt = $this->db->prepare("
            SELECT s.*, m.fio as mijoz_fio, m.telefon as mijoz_tel
            FROM savdolar s
            LEFT JOIN mijozlar m ON s.mijoz_id = m.id
            WHERE s.id = ? AND s.tolov_holati IN ('NASIYA', 'QISMAN')
        ");
        $stmt->execute([$savdoId]);
        $sale = $stmt->fetch();
        
        if (!$sale) {
            $_SESSION['flash']['error'] = 'Savdo topilmadi yoki to\'lov qilingan';
            $this->redirect('debt');
        }
        
        $this->view('debt/payment', ['sale' => $sale]);
    }
    
    /**
     * To'lovni saqlash
     */
    public function storePayment() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('debt');
        }
        
        $rules = [
            'savdo_id' => 'required|numeric',
            'mijoz_id' => 'required|numeric',
            'summa' => 'required|numeric|min:1',
            'usul' => 'required'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('debt/payment/' . $_POST['savdo_id']);
        }
        
        $savdoId = $_POST['savdo_id'];
        $mijozId = $_POST['mijoz_id'];
        $summa = floatval(str_replace(',', '', $_POST['summa']));
        $usul = $_POST['usul'];
        $izoh = $_POST['izoh'] ?? '';
        
        // Qolgan qarzni tekshirish
        $stmt = $this->db->prepare("SELECT qarz_summa FROM savdolar WHERE id = ?");
        $stmt->execute([$savdoId]);
        $qolganQarz = $stmt->fetch()['qarz_summa'];
        
        if ($summa > $qolganQarz) {
            $_SESSION['flash']['error'] = 'To\'lov summasi qarzdan oshib ketdi. Qolgan qarz: ' . number_format($qolganQarz, 0, ',', ' ') . ' so\'m';
            $this->redirect('debt/payment/' . $savdoId);
        }
        
        $data = [
            'savdo_id' => $savdoId,
            'mijoz_id' => $mijozId,
            'usul' => $usul,
            'summa' => $summa,
            'izoh' => $izoh
        ];
        
        try {
            $this->debtModel->addPayment($data, $_SESSION['user_id']);
            
            if ($summa == $qolganQarz) {
                $_SESSION['flash']['success'] = 'To\'lov qabul qilindi. Qarz to\'liq yopildi.';
            } else {
                $_SESSION['flash']['success'] = 'To\'lov qabul qilindi. Qolgan qarz: ' . number_format($qolganQarz - $summa, 0, ',', ' ') . ' so\'m';
            }
            
            $this->redirect('debt/customer/' . $mijozId);
            
        } catch (\Exception $e) {
            error_log("Payment store error: " . $e->getMessage());
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
            $this->redirect('debt/payment/' . $savdoId);
        }
    }
    
    /**
     * Mijoz qarzini to'lash (to'liq)
     */
    public function payFull($savdoId) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('debt');
        }
        
        $stmt = $this->db->prepare("
            SELECT s.*, m.fio 
            FROM savdolar s
            LEFT JOIN mijozlar m ON s.mijoz_id = m.id
            WHERE s.id = ?
        ");
        $stmt->execute([$savdoId]);
        $sale = $stmt->fetch();
        
        if (!$sale || $sale['qarz_summa'] <= 0) {
            $_SESSION['flash']['error'] = 'Qarz topilmadi';
            $this->redirect('debt');
        }
        
        $data = [
            'savdo_id' => $savdoId,
            'mijoz_id' => $sale['mijoz_id'],
            'usul' => $_POST['usul'] ?? 'NAQD',
            'summa' => $sale['qarz_summa'],
            'izoh' => 'Qarzni to\'liq yopish'
        ];
        
        try {
            $this->debtModel->addPayment($data, $_SESSION['user_id']);
            $_SESSION['flash']['success'] = 'Qarz to\'liq yopildi. Mijoz: ' . $sale['fio'];
            $this->redirect('debt/customer/' . $sale['mijoz_id']);
            
        } catch (\Exception $e) {
            error_log("Pay full error: " . $e->getMessage());
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
            $this->redirect('debt');
        }
    }
}