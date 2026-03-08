<?php
namespace App\Controllers;

abstract class Controller {
    protected $db;
    
    protected $companyName = 'POS Magazin';

    
    public function __construct() {
        $this->db = \Database::getInstance();
        $this->loadCompanyName();
    }
    
    /**
     * Viewni layout bilan chiqarish
     */
    protected function view($view, $data = [], $layout = 'main') {
    // View faylining to'liq yo'li
        $viewPath = APP_PATH . '/Views/pages/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            die("View topilmadi: " . $viewPath);
        }
        
        // View content ni olish
        ob_start();
        extract($data);
        require $viewPath;
        $content = ob_get_clean();
        
        // Layout uchun title
        $title = $data['title'] ?? $this->getTitleFromView($view);
        
        // Layout ni chiqarish
        $layoutPath = APP_PATH . '/Views/layouts/' . $layout . '.php';
        if (!file_exists($layoutPath)) {
            die("Layout topilmadi: " . $layoutPath);
        }
        
        require $layoutPath;
    }
    /**
     * View nomidan title yaratish
     */
    private function getTitleFromView($view) {
        $parts = explode('/', $view);
        $last = end($parts);
        
        $titles = [
            'dashboard' => 'Dashboard',
            'products/index' => 'Mahsulotlar',
            'products/create' => 'Yangi mahsulot',
            'products/edit' => 'Mahsulotni tahrirlash',
            'categories/index' => 'Kategoriyalar',
            'categories/create' => 'Yangi kategoriya',
            'categories/edit' => 'Kategoriyani tahrirlash',
            'subcategories/index' => 'Subkategoriyalar',
            'subcategories/create' => 'Yangi subkategoriya',
            'subcategories/edit' => 'Subkategoriyani tahrirlash',
            'customers/index' => 'Mijozlar',
            'customers/create' => 'Yangi mijoz',
            'customers/edit' => 'Mijozni tahrirlash',
            'debt/index' => 'Qarzdorlar',
            'debt/customer' => 'Mijoz qarzi',
            'debt/payment' => 'To\'lov qabul qilish',
            'returns/index' => 'Mahsulot qaytarish',
            'returns/details' => 'Qaytarish detallari',
            'returns/history' => 'Qaytarish tarixi',
            'reports/index' => 'Hisobotlar',
            'reports/monthly' => 'Oylik hisobot',
            'reports/profit' => 'Foyda hisoboti',
            'pos/index' => 'POS - Savdo',
            'pos/receipt' => 'Chek',
            'settings/index' => 'Sozlamalar',
            'settings/company' => 'Kompaniya sozlamalari',
            'settings/currency' => 'Valyuta sozlamalari',
            'settings/pos' => 'POS sozlamalari',
            'settings/users' => 'Foydalanuvchilar',
            'settings/user_create' => 'Yangi foydalanuvchi',
            'settings/user_edit' => 'Foydalanuvchini tahrirlash',
            'settings/profile' => 'Mening profilim',
        ];
        
        return $titles[$view] ?? ucfirst(str_replace('/', ' ', $view));
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        if (strpos($url, 'http') === 0) {
            header("Location: $url");
        } else {
            header("Location: /new-pos/" . ltrim($url, '/'));
        }
        exit;
    }
    
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? '';
            
            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field][] = "$field maydoni to'ldirilishi shart";
            }
            
            if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field][] = "$field maydoni email formatida bo'lishi kerak";
            }
            
            if (preg_match('/min:(\d+)/', $rule, $matches)) {
                if (strlen($value) < $matches[1]) {
                    $errors[$field][] = "$field maydoni kamida {$matches[1]} belgi bo'lishi kerak";
                }
            }
            
            if (preg_match('/max:(\d+)/', $rule, $matches)) {
                if (strlen($value) > $matches[1]) {
                    $errors[$field][] = "$field maydoni ko'pi bilan {$matches[1]} belgi bo'lishi kerak";
                }
            }
            
            if (preg_match('/numeric/', $rule) && !is_numeric($value)) {
                $errors[$field][] = "$field maydoni raqam bo'lishi kerak";
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            return false;
        }
        
        return true;
    }
    /**
     * Kompaniya nomini yuklash
     */
    protected function loadCompanyName() {
        try {
            $stmt = $this->db->prepare("SELECT qiymat FROM sozlamalar WHERE kalit_soz = 'company_name'");
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result && !empty($result['qiymat'])) {
                $this->companyName = $result['qiymat'];
            }
        } catch (\Exception $e) {
            // Xatolik bo'lsa, default qiymat ishlatiladi
        }
    }
}