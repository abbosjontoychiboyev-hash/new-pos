<?php
// app/Controllers/DashboardController.php

namespace App\Controllers;

class DashboardController extends Controller {
    
    public function index() {
        // Faqat login qilganlar uchun
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash']['error'] = 'Iltimos, avval tizimga kiring';
            $this->redirect('login');
        }
        
        // Dashboard view ni ko'rsatish
        $this->view('dashboard/index');
    }
}