<?php
// app/Controllers/SettingController.php

namespace App\Controllers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Role;

class SettingController extends Controller {
    
    private $settingModel;
    private $userModel;
    private $roleModel;
    
    public function __construct() {
        parent::__construct();
        $this->settingModel = new Setting();
        $this->userModel = new User();
        $this->roleModel = new Role();
    }
    
    /**
     * Sozlamalar asosiy sahifasi
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        // Faqat admin ruxsati
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda sozlamalarga kirish ruxsati yo\'q';
            $this->redirect('dashboard');
        }
        
        $this->view('settings/index');
    }
    
    /**
     * Kompaniya sozlamalari
     */
    public function company() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda sozlamalarga kirish ruxsati yo\'q';
            $this->redirect('dashboard');
        }
        
        $companyInfo = $this->settingModel->getCompanyInfo();
        
        $this->view('settings/company', [
            'company' => $companyInfo
        ]);
    }
    
    /**
     * Kompaniya sozlamalarini saqlash
     */
    public function saveCompany() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('settings/company');
        }
        
        $settings = [
            'company_name' => $_POST['company_name'] ?? '',
            'company_address' => $_POST['company_address'] ?? '',
            'company_phone' => $_POST['company_phone'] ?? '',
            'company_email' => $_POST['company_email'] ?? '',
            'company_tax_number' => $_POST['company_tax_number'] ?? ''
        ];
        
        // Logo yuklash
        if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] == 0) {
            $uploadDir = UPLOAD_PATH . '/logo/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $extension = pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION);
            $filename = 'logo_' . time() . '.' . $extension;
            $uploadFile = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $uploadFile)) {
                $settings['company_logo'] = 'uploads/logo/' . $filename;
            }
        }
        
        try {
            foreach ($settings as $key => $value) {
                $this->settingModel->set($key, $value);
            }
            
            $_SESSION['flash']['success'] = 'Kompaniya sozlamalari saqlandi';
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
        }
        
        $this->redirect('settings/company');
    }
    
    /**
     * Valyuta sozlamalari
     */
    public function currency() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda sozlamalarga kirish ruxsati yo\'q';
            $this->redirect('dashboard');
        }
        
        $currencySettings = $this->settingModel->getCurrencySettings();
        
        $this->view('settings/currency', [
            'currency' => $currencySettings
        ]);
    }
    
    /**
     * Valyuta sozlamalarini saqlash
     */
    public function saveCurrency() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('settings/currency');
        }
        
        $settings = [
            'currency_name' => $_POST['currency_name'] ?? 'so\'m',
            'currency_symbol' => $_POST['currency_symbol'] ?? 'so\'m',
            'currency_position' => $_POST['currency_position'] ?? 'right',
            'decimal_places' => $_POST['decimal_places'] ?? 0,
            'thousand_separator' => $_POST['thousand_separator'] ?? ' '
        ];
        
        try {
            foreach ($settings as $key => $value) {
                $this->settingModel->set($key, $value);
            }
            
            $_SESSION['flash']['success'] = 'Valyuta sozlamalari saqlandi';
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
        }
        
        $this->redirect('settings/currency');
    }
    
    /**
     * POS sozlamalari
     */
    public function pos() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda sozlamalarga kirish ruxsati yo\'q';
            $this->redirect('dashboard');
        }
        
        $posSettings = $this->settingModel->getPosSettings();
        
        $this->view('settings/pos', [
            'pos' => $posSettings
        ]);
    }
    
    /**
     * POS sozlamalarini saqlash
     */
    public function savePos() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('settings/pos');
        }
        
        $settings = [
            'receipt_header' => $_POST['receipt_header'] ?? '',
            'receipt_footer' => $_POST['receipt_footer'] ?? '',
            'auto_print_receipt' => isset($_POST['auto_print_receipt']) ? 1 : 0,
            'show_customer_on_receipt' => isset($_POST['show_customer_on_receipt']) ? 1 : 0,
            'default_payment_method' => $_POST['default_payment_method'] ?? 'NAQD'
        ];
        
        try {
            foreach ($settings as $key => $value) {
                $this->settingModel->set($key, $value);
            }
            
            $_SESSION['flash']['success'] = 'POS sozlamalari saqlandi';
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
        }
        
        $this->redirect('settings/pos');
    }
    
    /**
     * Foydalanuvchilar ro'yxati
     */
    public function users() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda foydalanuvchilarni boshqarish ruxsati yo\'q';
            $this->redirect('dashboard');
        }
        
        $users = $this->userModel->getActive();
        $roles = $this->roleModel->getAllWithCount();
        
        $this->view('settings/users', [
            'users' => $users,
            'roles' => $roles
        ]);
    }
    
    /**
     * Yangi foydalanuvchi qo'shish
     */
    public function userCreate() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda foydalanuvchilarni boshqarish ruxsati yo\'q';
            $this->redirect('dashboard');
        }
        
        $roles = $this->roleModel->getAllWithCount();
        
        $this->view('settings/user_create', [
            'roles' => $roles
        ]);
    }
    
    /**
     * Foydalanuvchini saqlash
     */
    public function userStore() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('settings/users');
        }
        
        $rules = [
            'fio' => 'required|min:3|max:120',
            'login' => 'required|min:3|max:60',
            'password' => 'required|min:6',
            'rol_id' => 'required|numeric'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('settings/users/create');
        }
        
        // Login unikal ekanligini tekshirish
        $existing = $this->userModel->findByLogin($_POST['login']);
        if ($existing) {
            $_SESSION['flash']['error'] = 'Bu login allaqachon mavjud';
            $_SESSION['old'] = $_POST;
            $this->redirect('settings/users/create');
        }
        
        $data = [
            'rol_id' => $_POST['rol_id'],
            'fio' => $_POST['fio'],
            'login' => $_POST['login'],
            'email' => $_POST['email'] ?? null,
            'telefon' => $_POST['telefon'] ?? null,
            'parol_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'faol' => isset($_POST['faol']) ? 1 : 1
        ];
        
        try {
            $id = $this->userModel->create($data);
            $_SESSION['flash']['success'] = 'Foydalanuvchi qo\'shildi';
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
        }
        
        $this->redirect('settings/users');
    }
    
    /**
     * Foydalanuvchini tahrirlash
     */
    public function userEdit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda foydalanuvchilarni boshqarish ruxsati yo\'q';
            $this->redirect('dashboard');
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            $_SESSION['flash']['error'] = 'Foydalanuvchi topilmadi';
            $this->redirect('settings/users');
        }
        
        $roles = $this->roleModel->getAllWithCount();
        
        $this->view('settings/user_edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }
    
    /**
     * Foydalanuvchini yangilash
     */
    public function userUpdate($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('settings/users');
        }
        
        $rules = [
            'fio' => 'required|min:3|max:120',
            'login' => 'required|min:3|max:60',
            'rol_id' => 'required|numeric'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('settings/users/edit/' . $id);
        }
        
        // Login unikal ekanligini tekshirish (o'zidan boshqa)
        $existing = $this->userModel->findByLogin($_POST['login']);
        if ($existing && $existing['id'] != $id) {
            $_SESSION['flash']['error'] = 'Bu login allaqachon mavjud';
            $this->redirect('settings/users/edit/' . $id);
        }
        
        $data = [
            'rol_id' => $_POST['rol_id'],
            'fio' => $_POST['fio'],
            'login' => $_POST['login'],
            'email' => $_POST['email'] ?? null,
            'telefon' => $_POST['telefon'] ?? null,
            'faol' => isset($_POST['faol']) ? 1 : 0
        ];
        
        // Agar parol o'zgartirilgan bo'lsa
        if (!empty($_POST['password'])) {
            $data['parol_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        
        if ($this->userModel->update($id, $data)) {
            $_SESSION['flash']['success'] = 'Foydalanuvchi yangilandi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('settings/users');
    }
    
    /**
     * Foydalanuvchini o'chirish (soft delete)
     */
    public function userDelete($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('settings/users');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda foydalanuvchilarni o\'chirish ruxsati yo\'q';
            $this->redirect('settings/users');
        }
        
        // O'zini o'chirishni oldini olish
        if ($id == $_SESSION['user_id']) {
            $_SESSION['flash']['error'] = 'O\'zingizni o\'chira olmaysiz';
            $this->redirect('settings/users');
        }
        
        if ($this->userModel->delete($id)) {
            $_SESSION['flash']['success'] = 'Foydalanuvchi o\'chirildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('settings/users');
    }
    
    /**
     * Profil (o'z ma'lumotlari)
     */
    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $user = $this->userModel->find($_SESSION['user_id']);
        
        $this->view('settings/profile', [
            'user' => $user
        ]);
    }
    
    /**
     * Profilni yangilash
     */
    public function profileUpdate() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('settings/profile');
        }
        
        $userId = $_SESSION['user_id'];
        
        $rules = [
            'fio' => 'required|min:3|max:120'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('settings/profile');
        }
        
        $data = [
            'fio' => $_POST['fio'],
            'email' => $_POST['email'] ?? null,
            'telefon' => $_POST['telefon'] ?? null
        ];
        
        $this->userModel->update($userId, $data);
        
        // Parolni o'zgartirish
        if (!empty($_POST['current_password']) && !empty($_POST['new_password'])) {
            if ($this->userModel->verifyPassword($userId, $_POST['current_password'])) {
                if ($_POST['new_password'] === $_POST['confirm_password']) {
                    $this->userModel->updatePassword($userId, $_POST['new_password']);
                    $_SESSION['flash']['success'] = 'Profil va parol yangilandi';
                } else {
                    $_SESSION['flash']['error'] = 'Yangi parollar mos kelmadi';
                }
            } else {
                $_SESSION['flash']['error'] = 'Joriy parol noto\'g\'ri';
            }
        } else {
            $_SESSION['flash']['success'] = 'Profil yangilandi';
        }
        
        $this->redirect('settings/profile');
    }
}