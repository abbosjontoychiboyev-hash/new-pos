<?php
// app/Controllers/UserController.php

namespace App\Controllers;

use App\Models\User;
use App\Models\Role;

class UserController extends Controller {
    
    private $userModel;
    private $roleModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->roleModel = new Role();
    }
    
    /**
     * Foydalanuvchilar ro'yxati
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda foydalanuvchilarni ko\'rish ruxsati yo\'q';
            $this->redirect('dashboard');
        }
        
        $users = $this->userModel->all();
        $roles = $this->roleModel->all();
        
        $this->view('settings/users', [
            'users' => $users,
            'roles' => $roles
        ]);
    }
    
    /**
     * Yangi foydalanuvchi qo'shish formasi
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda foydalanuvchi qo\'shish ruxsati yo\'q';
            $this->redirect('settings/users');
        }
        
        $roles = $this->roleModel->all();
        
        $this->view('settings/user_create', [
            'roles' => $roles
        ]);
    }
    
    /**
     * Yangi foydalanuvchini saqlash
     */
    public function store() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('settings/users/create');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda foydalanuvchi qo\'shish ruxsati yo\'q';
            $this->redirect('settings/users');
        }
        
        $rules = [
            'fio' => 'required|min:3|max:120',
            'login' => 'required|min:3|max:60|unique:foydalanuvchilar,login',
            'email' => 'email|max:120',
            'parol' => 'required|min:6',
            'rol_id' => 'required|numeric'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('settings/users/create');
        }
        
        $data = [
            'rol_id' => $_POST['rol_id'],
            'fio' => $_POST['fio'],
            'email' => $_POST['email'] ?? null,
            'telefon' => $_POST['telefon'] ?? null,
            'login' => $_POST['login'],
            'parol_hash' => password_hash($_POST['parol'], PASSWORD_DEFAULT),
            'faol' => isset($_POST['faol']) ? 1 : 1
        ];
        
        try {
            $userId = $this->userModel->create($data);
            if ($userId) {
                $_SESSION['flash']['success'] = 'Foydalanuvchi qo\'shildi';
                $this->redirect('settings/users');
            } else {
                $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
                $_SESSION['old'] = $_POST;
                $this->redirect('settings/users/create');
            }
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik: ' . $e->getMessage();
            $_SESSION['old'] = $_POST;
            $this->redirect('settings/users/create');
        }
    }
    
    /**
     * Foydalanuvchi tahrirlash formasi
     */
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda foydalanuvchi tahrirlash ruxsati yo\'q';
            $this->redirect('settings/users');
        }
        
        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['flash']['error'] = 'Foydalanuvchi topilmadi';
            $this->redirect('settings/users');
        }
        
        $roles = $this->roleModel->all();
        
        $this->view('settings/user_edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }
    
    /**
     * Foydalanuvchini yangilash
     */
    public function update($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect("settings/users/edit/$id");
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda foydalanuvchi yangilash ruxsati yo\'q';
            $this->redirect('settings/users');
        }
        
        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['flash']['error'] = 'Foydalanuvchi topilmadi';
            $this->redirect('settings/users');
        }
        
        $rules = [
            'fio' => 'required|min:3|max:120',
            'login' => 'required|min:3|max:60|unique:foydalanuvchilar,login,' . $id,
            'email' => 'email|max:120',
            'rol_id' => 'required|numeric'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect("settings/users/edit/$id");
        }
        
        $data = [
            'rol_id' => $_POST['rol_id'],
            'fio' => $_POST['fio'],
            'email' => $_POST['email'] ?? null,
            'telefon' => $_POST['telefon'] ?? null,
            'login' => $_POST['login'],
            'faol' => isset($_POST['faol']) ? 1 : 0
        ];
        
        // Parolni yangilash
        if (!empty($_POST['parol'])) {
            if (strlen($_POST['parol']) < 6) {
                $_SESSION['flash']['error'] = 'Parol kamida 6 ta belgidan iborat bo\'lishi kerak';
                $this->redirect("settings/users/edit/$id");
            }
            $data['parol_hash'] = password_hash($_POST['parol'], PASSWORD_DEFAULT);
        }
        
        try {
            if ($this->userModel->update($id, $data)) {
                $_SESSION['flash']['success'] = 'Foydalanuvchi yangilandi';
                $this->redirect('settings/users');
            } else {
                $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
                $this->redirect("settings/users/edit/$id");
            }
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik: ' . $e->getMessage();
            $this->redirect("settings/users/edit/$id");
        }
    }
    
    /**
     * Foydalanuvchini o'chirish
     */
    public function delete($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('settings/users');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda foydalanuvchi o\'chirish ruxsati yo\'q';
            $this->redirect('settings/users');
        }
        
        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['flash']['error'] = 'Foydalanuvchi topilmadi';
            $this->redirect('settings/users');
        }
        
        // O'zini o'chirishga ruxsat yo'q
        if ($id == $_SESSION['user_id']) {
            $_SESSION['flash']['error'] = 'O\'zingizni o\'chirib bo\'lmaydi';
            $this->redirect('settings/users');
        }
        
        try {
            if ($this->userModel->delete($id)) {
                $_SESSION['flash']['success'] = 'Foydalanuvchi o\'chirildi';
            } else {
                $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
            }
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik: ' . $e->getMessage();
        }
        
        $this->redirect('settings/users');
    }
    
    /**
     * Foydalanuvchi tafsilotlari
     */
    public function show($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda foydalanuvchi tafsilotlarini ko\'rish ruxsati yo\'q';
            $this->redirect('settings/users');
        }
        
        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['flash']['error'] = 'Foydalanuvchi topilmadi';
            $this->redirect('settings/users');
        }
        
        $this->view('settings/user_show', [
            'user' => $user
        ]);
    }
}