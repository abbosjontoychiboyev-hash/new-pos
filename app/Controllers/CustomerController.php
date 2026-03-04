<?php
// app/Controllers/CustomerController.php

namespace App\Controllers;

use App\Models\Customer;

class CustomerController extends Controller {
    
    private $customerModel;
    
    public function __construct() {
        parent::__construct();
        $this->customerModel = new Customer();
    }
    
    /**
     * Mijozlar ro'yxati
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $customers = $this->customerModel->search($search);
            $pagination = ['page' => 1, 'lastPage' => 1, 'total' => count($customers)];
        } else {
            // Pagination qilish kerak
            $customers = $this->customerModel->all();
            $pagination = ['page' => 1, 'lastPage' => 1, 'total' => count($customers)];
        }
        
        $this->view('customers/index', [
            'customers' => $customers,
            'search' => $search,
            'pagination' => $pagination
        ]);
    }
    
    /**
     * Yangi mijoz qo'shish formasi
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $this->view('customers/create');
    }
    
    /**
     * Yangi mijozni saqlash
     */
    public function store() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('customers/create');
        }
        
        $rules = [
            'fio' => 'required|min:3|max:160'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('customers/create');
        }
        
        $data = [
            'fio' => $_POST['fio'],
            'telefon' => $_POST['telefon'] ?? null,
            'manzil' => $_POST['manzil'] ?? null,
            'izoh' => $_POST['izoh'] ?? null,
            'faol' => isset($_POST['faol']) ? 1 : 1
        ];
        
        try {
            $id = $this->customerModel->create($data);
            $_SESSION['flash']['success'] = 'Mijoz muvaffaqiyatli qo\'shildi';
            $this->redirect('customers');
        } catch (\Exception $e) {
            error_log("Customer store error: " . $e->getMessage());
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
            $_SESSION['old'] = $_POST;
            $this->redirect('customers/create');
        }
    }
    
    /**
     * Mijozni tahrirlash formasi
     */
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            $_SESSION['flash']['error'] = 'Mijoz topilmadi';
            $this->redirect('customers');
        }
        
        $this->view('customers/edit', ['customer' => $customer]);
    }
    
    /**
     * Mijozni yangilash
     */
    public function update($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect("customers/edit/$id");
        }
        
        $rules = [
            'fio' => 'required|min:3|max:160'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect("customers/edit/$id");
        }
        
        $data = [
            'fio' => $_POST['fio'],
            'telefon' => $_POST['telefon'] ?? null,
            'manzil' => $_POST['manzil'] ?? null,
            'izoh' => $_POST['izoh'] ?? null,
            'faol' => isset($_POST['faol']) ? 1 : 1
        ];
        
        if ($this->customerModel->update($id, $data)) {
            $_SESSION['flash']['success'] = 'Mijoz yangilandi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('customers');
    }
    
    /**
     * Mijozni o'chirish (soft delete)
     */
    public function delete($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('customers');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda mijoz o\'chirish ruxsati yo\'q';
            $this->redirect('customers');
        }
        
        // Savdolar borligini tekshirish
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM savdolar WHERE mijoz_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetch()['count'];
        
        if ($count > 0) {
            $_SESSION['flash']['error'] = 'Bu mijozning savdolari mavjud, o\'chirib bo\'lmaydi';
            $this->redirect('customers');
        }
        
        if ($this->customerModel->delete($id)) {
            $_SESSION['flash']['success'] = 'Mijoz o\'chirildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('customers');
    }
    
    /**
     * Mijoz qarzini ko'rish
     */
    public function debt($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            $_SESSION['flash']['error'] = 'Mijoz topilmadi';
            $this->redirect('customers');
        }
        
        $debt = $this->customerModel->getDebt($id);
        $payments = $this->customerModel->getPayments($id);
        $sales = $this->customerModel->getSales($id);
        $stats = $this->customerModel->getStats($id);
        
        $this->view('customers/debt', [
            'customer' => $customer,
            'debt' => $debt,
            'payments' => $payments,
            'sales' => $sales,
            'stats' => $stats
        ]);
    }
}