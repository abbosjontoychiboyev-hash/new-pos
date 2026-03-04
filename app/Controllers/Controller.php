<?php
// app/Controllers/Controller.php

namespace App\Controllers;

abstract class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = \Database::getInstance();
    }
    
    protected function view($view, $data = []) {
        // View faylining to'liq yo'li
        $viewPath = APP_PATH . '/Views/pages/' . $view . '.php';
        
        // Extract data ni o'zgaruvchilarga aylantirish
        extract($data);
        
        if (!file_exists($viewPath)) {
            die("View topilmadi: " . $viewPath);
        }
        
        // View ni yuklash
        require_once $viewPath;
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        // URL boshida / borligiga tekshirish
        if (strpos($url, 'http') === 0) {
            header("Location: $url");
        } else {
            header("Location: /" . ltrim($url, '/'));
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
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            return false;
        }
        
        return true;
    }
}