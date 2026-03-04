<?php
// app/Controllers/SubcategoryController.php

namespace App\Controllers;

use App\Models\Subcategory;
use App\Models\Category;

class SubcategoryController extends Controller {
    
    private $subcategoryModel;
    private $categoryModel;
    
    public function __construct() {
        parent::__construct();
        $this->subcategoryModel = new Subcategory();
        $this->categoryModel = new Category();
    }
    
    /**
     * Subkategoriyalar ro'yxati
     */
    public function index() {
    $search = $_GET['search'] ?? '';
        
        if ($search) {
            $stmt = $this->db->prepare("
                SELECT s.*, k.nomi as kategoriya_nomi 
                FROM subkategoriyalar s
                LEFT JOIN kategoriyalar k ON s.kategoriya_id = k.id
                WHERE s.ochirilgan_vaqt IS NULL 
                AND s.nomi LIKE ?
                ORDER BY k.nomi, s.nomi
            ");
            $stmt->execute(["%{$search}%"]);
            $subcategories = $stmt->fetchAll();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        $categoryId = $_GET['category'] ?? '';
        
        if ($search) {
            // Qidiruv
            $stmt = $this->db->prepare("
                SELECT s.*, k.nomi as kategoriya_nomi 
                FROM subkategoriyalar s
                LEFT JOIN kategoriyalar k ON s.kategoriya_id = k.id
                WHERE s.ochirilgan_vaqt IS NULL 
                AND s.nomi LIKE ?
                ORDER BY k.nomi, s.nomi
            ");
            $stmt->execute(["%{$search}%"]);
            $subcategories = $stmt->fetchAll();
            $pagination = ['page' => 1, 'lastPage' => 1, 'total' => count($subcategories)];
        } elseif ($categoryId) {
            // Kategoriya bo'yicha filter
            $subcategories = $this->subcategoryModel->getByCategory($categoryId, false);
            $pagination = ['page' => 1, 'lastPage' => 1, 'total' => count($subcategories)];
        } else {
            // Hammasi
            $subcategories = $this->subcategoryModel->getAllWithCategory();
            $pagination = ['page' => 1, 'lastPage' => 1, 'total' => count($subcategories)];
        }
        
        $categories = $this->categoryModel->active();
        
        $this->view('subcategories/index', [
            'subcategories' => $subcategories,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'search' => $search,
            'pagination' => $pagination
        ]);
    }
    
    /**
     * Yangi subkategoriya qo'shish formasi
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if (!in_array($_SESSION['user']['rol_nomi'], ['Admin', 'Omborchi'])) {
            $_SESSION['flash']['error'] = 'Sizda subkategoriya qo\'shish ruxsati yo\'q';
            $this->redirect('subcategories');
        }
        
        $categories = $this->categoryModel->active();
        $this->view('subcategories/create', ['categories' => $categories]);
    }
    
    /**
     * Yangi subkategoriyani saqlash
     */
    public function store() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('subcategories/create');
        }
        
        $rules = [
            'nomi' => 'required|min:2|max:120',
            'kategoriya_id' => 'required|numeric'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('subcategories/create');
        }
        
        // Tartib raqam
        $tartib = $_POST['tartib'] ?? 0;
        if (empty($tartib)) {
            $stmt = $this->db->prepare("
                SELECT MAX(tartib) as max_tartib 
                FROM subkategoriyalar 
                WHERE kategoriya_id = ?
            ");
            $stmt->execute([$_POST['kategoriya_id']]);
            $max = $stmt->fetch()['max_tartib'];
            $tartib = ($max ?? 0) + 1;
        }
        
        $data = [
            'kategoriya_id' => $_POST['kategoriya_id'],
            'nomi' => $_POST['nomi'],
            'izoh' => $_POST['izoh'] ?? null,
            'faol' => isset($_POST['faol']) ? 1 : 0,
            'tartib' => $tartib
        ];
        
        try {
            $id = $this->subcategoryModel->create($data);
            $_SESSION['flash']['success'] = 'Subkategoriya muvaffaqiyatli qo\'shildi';
            $this->redirect('subcategories');
        } catch (\Exception $e) {
            error_log("Subcategory store error: " . $e->getMessage());
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
            $_SESSION['old'] = $_POST;
            $this->redirect('subcategories/create');
        }
    }
    
    /**
     * Subkategoriyani tahrirlash formasi
     */
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if (!in_array($_SESSION['user']['rol_nomi'], ['Admin', 'Omborchi'])) {
            $_SESSION['flash']['error'] = 'Sizda subkategoriya tahrirlash ruxsati yo\'q';
            $this->redirect('subcategories');
        }
        
        $subcategory = $this->subcategoryModel->find($id);
        
        if (!$subcategory) {
            $_SESSION['flash']['error'] = 'Subkategoriya topilmadi';
            $this->redirect('subcategories');
        }
        
        $categories = $this->categoryModel->active();
        
        $this->view('subcategories/edit', [
            'subcategory' => $subcategory,
            'categories' => $categories
        ]);
    }
    
    /**
     * Subkategoriyani yangilash
     */
    public function update($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect("subcategories/edit/$id");
        }
        
        $rules = [
            'nomi' => 'required|min:2|max:120',
            'kategoriya_id' => 'required|numeric'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect("subcategories/edit/$id");
        }
        
        $data = [
            'kategoriya_id' => $_POST['kategoriya_id'],
            'nomi' => $_POST['nomi'],
            'izoh' => $_POST['izoh'] ?? null,
            'faol' => isset($_POST['faol']) ? 1 : 0,
            'tartib' => $_POST['tartib'] ?? 0
        ];
        
        if ($this->subcategoryModel->update($id, $data)) {
            $_SESSION['flash']['success'] = 'Subkategoriya yangilandi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('subcategories');
    }
    
    /**
     * Subkategoriyani o'chirish (soft delete)
     */
    public function delete($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('subcategories');
        }
        
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda subkategoriya o\'chirish ruxsati yo\'q';
            $this->redirect('subcategories');
        }
        
        // Mahsulotlar borligini tekshirish
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM mahsulotlar WHERE subkategoriya_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetch()['count'];
        
        if ($count > 0) {
            $_SESSION['flash']['error'] = 'Bu subkategoriyada mahsulotlar mavjud';
            $this->redirect('subcategories');
        }
        
        if ($this->subcategoryModel->delete($id)) {
            $_SESSION['flash']['success'] = 'Subkategoriya o\'chirildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('subcategories');
    }
}