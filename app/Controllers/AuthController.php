<?php
// app/Controllers/AuthController.php

namespace App\Controllers;

class AuthController extends Controller {
    
    public function loginForm() {
        // Agar user allaqachon kirgan bo'lsa
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        
        // Yangi CSRF token yaratish
        csrf_token();
        
        // View ni ko'rsatish
        $this->view('auth/login');
    }
    
    public function login() {
        // Debug uchun
        error_log("Login POST: " . print_r($_POST, true));
        error_log("Session CSRF: " . ($_SESSION['csrf_token'] ?? 'not set'));
        
        // CSRF tekshirish - to'g'ri usul
        if (!isset($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token topilmadi';
            $this->redirect('login');
        }
        
        if (!validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato yoki eskirgan. Iltimos, qaytadan urinib ko\'ring.';
            $this->redirect('login');
        }
        
        // Validatsiya
        $rules = [
            'login' => 'required|min:3|max:60',
            'password' => 'required|min:6'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('login');
        }
        
        $login = $_POST['login'];
        $password = $_POST['password'];
        
        try {
            $stmt = $this->db->prepare("
                SELECT f.*, r.nomi as rol_nomi 
                FROM foydalanuvchilar f
                JOIN rollar r ON f.rol_id = r.id
                WHERE f.login = ? AND f.faol = 1 AND f.ochirilgan_vaqt IS NULL
            ");
            
            $stmt->execute([$login]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['parol_hash'])) {
                // Muvaffaqiyatli login - eski token ni o'chirish (xavfsizlik uchun)
                unset($_SESSION['csrf_token']);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'fio' => $user['fio'],
                    'rol_id' => $user['rol_id'],
                    'rol_nomi' => $user['rol_nomi'],
                    'login' => $user['login']
                ];
                
                // Oxirgi kirish vaqtini yangilash
                $stmt = $this->db->prepare("UPDATE foydalanuvchilar SET oxirgi_kirish_vaqt = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
                
                $_SESSION['flash']['success'] = 'Xush kelibsiz, ' . $user['fio'];
                $this->redirect('dashboard');
            } else {
                $_SESSION['flash']['error'] = 'Login yoki parol noto\'g\'ri';
                $this->redirect('login');
            }
        } catch (\Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $_SESSION['flash']['error'] = 'Tizim xatoligi yuz berdi';
            $this->redirect('login');
        }
    }
    
    
    public function logout() {
        session_destroy();
        $this->redirect('login');
    }
}