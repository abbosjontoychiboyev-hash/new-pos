<?php
// app/Controllers/RoleController.php

namespace App\Controllers;

use App\Models\Role;

class RoleController extends Controller {
    
    private $roleModel;
    
    public function __construct() {
        parent::__construct();
        $this->roleModel = new Role();
    }
    
    /**
     * Rollarni ro'yxati
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda rollarni ko\'rish ruxsati yo\'q';
            $this->redirect('dashboard');
        }
        
        $roles = $this->roleModel->getAllWithCount();
        
        $this->view('settings/roles', [
            'roles' => $roles
        ]);
    }
    
    /**
     * Yangi rol qo'shish formasi
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda rol qo\'shish ruxsati yo\'q';
            $this->redirect('settings/roles');
        }
        
        $this->view('settings/role_create');
    }
    
    /**
     * Yangi rolni saqlash
     */
    public function store() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('settings/roles/create');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda rol qo\'shish ruxsati yo\'q';
            $this->redirect('settings/roles');
        }
        
        $rules = [
            'nomi' => 'required|min:2|max:60|unique:rollar,nomi'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('settings/roles/create');
        }
        
        $data = [
            'nomi' => $_POST['nomi'],
            'izoh' => $_POST['izoh'] ?? null
        ];
        
        try {
            $roleId = $this->roleModel->create($data);
            if ($roleId) {
                $_SESSION['flash']['success'] = 'Rol qo\'shildi';
                $this->redirect('settings/roles');
            } else {
                $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
                $_SESSION['old'] = $_POST;
                $this->redirect('settings/roles/create');
            }
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik: ' . $e->getMessage();
            $_SESSION['old'] = $_POST;
            $this->redirect('settings/roles/create');
        }
    }
    
    /**
     * Rol tahrirlash formasi
     */
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda rol tahrirlash ruxsati yo\'q';
            $this->redirect('settings/roles');
        }
        
        $role = $this->roleModel->find($id);
        if (!$role) {
            $_SESSION['flash']['error'] = 'Rol topilmadi';
            $this->redirect('settings/roles');
        }
        
        $this->view('settings/role_edit', [
            'role' => $role
        ]);
    }
    
    /**
     * Rolni yangilash
     */
    public function update($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect("settings/roles/edit/$id");
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda rol yangilash ruxsati yo\'q';
            $this->redirect('settings/roles');
        }
        
        $role = $this->roleModel->find($id);
        if (!$role) {
            $_SESSION['flash']['error'] = 'Rol topilmadi';
            $this->redirect('settings/roles');
        }
        
        $rules = [
            'nomi' => 'required|min:2|max:60|unique:rollar,nomi,' . $id
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect("settings/roles/edit/$id");
        }
        
        $data = [
            'nomi' => $_POST['nomi'],
            'izoh' => $_POST['izoh'] ?? null
        ];
        
        try {
            if ($this->roleModel->update($id, $data)) {
                $_SESSION['flash']['success'] = 'Rol yangilandi';
                $this->redirect('settings/roles');
            } else {
                $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
                $this->redirect("settings/roles/edit/$id");
            }
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik: ' . $e->getMessage();
            $this->redirect("settings/roles/edit/$id");
        }
    }
    
    /**
     * Rolni o'chirish
     */
    public function delete($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('settings/roles');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda rol o\'chirish ruxsati yo\'q';
            $this->redirect('settings/roles');
        }
        
        $role = $this->roleModel->find($id);
        if (!$role) {
            $_SESSION['flash']['error'] = 'Rol topilmadi';
            $this->redirect('settings/roles');
        }
        
        // Admin rolini o'chirishga ruxsat yo'q
        if ($role['nomi'] === 'Admin') {
            $_SESSION['flash']['error'] = 'Admin rolini o\'chirib bo\'lmaydi';
            $this->redirect('settings/roles');
        }
        
        // Rolga tegishli foydalanuvchilar borligini tekshirish
        $userCount = $this->roleModel->getUserCount($id);
        if ($userCount > 0) {
            $_SESSION['flash']['error'] = 'Bu rolga tegishli foydalanuvchilar mavjud. Avval ularni boshqa rolga o\'tkazing';
            $this->redirect('settings/roles');
        }
        
        try {
            if ($this->roleModel->delete($id)) {
                $_SESSION['flash']['success'] = 'Rol o\'chirildi';
            } else {
                $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
            }
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik: ' . $e->getMessage();
        }
        
        $this->redirect('settings/roles');
    }
    
    /**
     * Rol tafsilotlari
     */
    public function show($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda rol tafsilotlarini ko\'rish ruxsati yo\'q';
            $this->redirect('settings/roles');
        }
        
        $role = $this->roleModel->find($id);
        if (!$role) {
            $_SESSION['flash']['error'] = 'Rol topilmadi';
            $this->redirect('settings/roles');
        }
        
        $userCount = $this->roleModel->getUserCount($id);
        
        $this->view('settings/role_show', [
            'role' => $role,
            'userCount' => $userCount
        ]);
    }
}