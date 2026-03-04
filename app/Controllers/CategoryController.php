<?php
// app/Controllers/CategoryController.php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Subcategory;

class CategoryController extends Controller {
    
    private $categoryModel;
    private $subcategoryModel;
    
    public function __construct() {
        parent::__construct();
        $this->categoryModel = new Category();
        $this->subcategoryModel = new Subcategory();
    }
    
    /**
     * Kategoriyalar ro'yxati
     */
    public function index() {
        $search = $_GET['search'] ?? '';
    
        if ($search) {
            $categories = $this->categoryModel->search($search);
        }
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $categories = $this->categoryModel->search($search);
            $pagination = ['page' => 1, 'lastPage' => 1, 'total' => count($categories)];
        } else {
            // Kategoriyalarni subkategoriyalar soni bilan olish
            $categories = $this->categoryModel->getWithSubcategories();
            $pagination = ['page' => 1, 'lastPage' => 1, 'total' => count($categories)];
        }
        
        $this->view('categories/index', [
            'categories' => $categories,
            'search' => $search,
            'pagination' => $pagination
        ]);
    }
    
    /**
     * Yangi kategoriya qo'shish formasi
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if (!in_array($_SESSION['user']['rol_nomi'], ['Admin', 'Omborchi'])) {
            $_SESSION['flash']['error'] = 'Sizda kategoriya qo\'shish ruxsati yo\'q';
            $this->redirect('categories');
        }
        
        $this->view('categories/create');
    }
    
    /**
     * Yangi kategoriyani saqlash
     */
    public function store() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('categories/create');
        }
        
        $rules = [
            'nomi' => 'required|min:2|max:120'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('categories/create');
        }
        
        // Tartib raqam
        $tartib = $_POST['tartib'] ?? 0;
        if (empty($tartib)) {
            // Eng katta tartib raqamni olish
            $stmt = $this->db->query("SELECT MAX(tartib) as max_tartib FROM kategoriyalar");
            $max = $stmt->fetch()['max_tartib'];
            $tartib = ($max ?? 0) + 1;
        }
        
        $data = [
            'nomi' => $_POST['nomi'],
            'izoh' => $_POST['izoh'] ?? null,
            'faol' => isset($_POST['faol']) ? 1 : 0,
            'tartib' => $tartib
        ];
        
        try {
            $id = $this->categoryModel->create($data);
            $_SESSION['flash']['success'] = 'Kategoriya muvaffaqiyatli qo\'shildi';
            $this->redirect('categories');
        } catch (\Exception $e) {
            error_log("Category store error: " . $e->getMessage());
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
            $_SESSION['old'] = $_POST;
            $this->redirect('categories/create');
        }
    }
    
    /**
     * Kategoriyani tahrirlash formasi
     */
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if (!in_array($_SESSION['user']['rol_nomi'], ['Admin', 'Omborchi'])) {
            $_SESSION['flash']['error'] = 'Sizda kategoriya tahrirlash ruxsati yo\'q';
            $this->redirect('categories');
        }
        
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            $_SESSION['flash']['error'] = 'Kategoriya topilmadi';
            $this->redirect('categories');
        }
        
        $this->view('categories/edit', ['category' => $category]);
    }
    
    /**
     * Kategoriyani yangilash
     */
    public function update($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect("categories/edit/$id");
        }
        
        $rules = [
            'nomi' => 'required|min:2|max:120'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect("categories/edit/$id");
        }
        
        $data = [
            'nomi' => $_POST['nomi'],
            'izoh' => $_POST['izoh'] ?? null,
            'faol' => isset($_POST['faol']) ? 1 : 0,
            'tartib' => $_POST['tartib'] ?? 0
        ];
        
        if ($this->categoryModel->update($id, $data)) {
            $_SESSION['flash']['success'] = 'Kategoriya yangilandi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('categories');
    }
    
    /**
     * Kategoriyani o'chirish (soft delete)
     */
    public function delete($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('categories');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda kategoriya o\'chirish ruxsati yo\'q';
            $this->redirect('categories');
        }
        
        // Subkategoriyalar borligini tekshirish
        $subcategories = $this->subcategoryModel->where(['kategoriya_id' => $id]);
        if (!empty($subcategories)) {
            $_SESSION['flash']['error'] = 'Bu kategoriyada subkategoriyalar mavjud. Avval ularni o\'chiring';
            $this->redirect('categories');
        }
        
        // Mahsulotlar borligini tekshirish
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM mahsulotlar WHERE kategoriya_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetch()['count'];
        
        if ($count > 0) {
            $_SESSION['flash']['error'] = 'Bu kategoriyada mahsulotlar mavjud';
            $this->redirect('categories');
        }
        
        if ($this->categoryModel->delete($id)) {
            $_SESSION['flash']['success'] = 'Kategoriya o\'chirildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('categories');
    }
}