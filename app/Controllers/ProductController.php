<?php
// app/Controllers/ProductController.php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;

class ProductController extends Controller {
    
    private $productModel;
    private $categoryModel;
    private $subcategoryModel;
    
    public function __construct() {
        parent::__construct();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->subcategoryModel = new Subcategory();
    }
    
    /**
     * Mahsulotlar ro'yxati
     */
    public function index() {
         $search = $_GET['search'] ?? '';
    
        if ($search) {
            // Product modeldagi search() metodi ishlaydi
            $products = $this->productModel->search($search);
        }
        // Faqat login qilganlar uchun
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        $categoryId = $_GET['category'] ?? '';
        
        if ($search) {
            // Qidiruv natijalari
            $products = $this->productModel->search($search);
            $pagination = [
                'page' => 1, 
                'lastPage' => 1, 
                'total' => count($products),
                'perPage' => count($products)
            ];
        } else {
            // Kategoriya filtri
            $conditions = [];
            if ($categoryId) {
                $conditions['kategoriya_id'] = $categoryId;
            }
            
            // Modelda paginate mavjud deb faraz qilamiz
            if (method_exists($this->productModel, 'paginate')) {
                $pagination = $this->productModel->paginate($page, 15, $conditions);
                $products = $pagination['data'];
            } else {
                // Agar paginate metodi bo'lmasa, oddiy qilib olamiz
                $products = $this->productModel->all();
                $pagination = [
                    'page' => 1,
                    'lastPage' => 1,
                    'total' => count($products),
                    'perPage' => count($products)
                ];
            }
        }
        
        // Kategoriyalar ro'yxati
        $categories = $this->categoryModel->all();
        
        $this->view('products/index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'search' => $search,
            'pagination' => $pagination
        ]);
    }
    
    /**
     * Yangi mahsulot qo'shish formasi
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        // Faqat admin va omborchi qo'sha oladi
        if (!in_array($_SESSION['user']['rol_nomi'], ['Admin', 'Omborchi'])) {
            $_SESSION['flash']['error'] = 'Sizda mahsulot qo\'shish ruxsati yo\'q';
            $this->redirect('products');
        }
        
        $categories = $this->categoryModel->all();
        
        $this->view('products/create', [
            'categories' => $categories
        ]);
    }
    
    /**
     * Kategoriya bo'yicha subkategoriyalarni olish (AJAX)
     */
    public function getSubcategories() {
        $categoryId = $_GET['category_id'] ?? 0;
        
        if (!$categoryId) {
            $this->json(['error' => 'Kategoriya ID talab qilinadi'], 400);
        }
        
        $subcategories = $this->subcategoryModel->where(['kategoriya_id' => $categoryId, 'faol' => 1]);
        $this->json($subcategories);
    }
    
    /**
     * Yangi mahsulotni saqlash
     */
    public function store() {
        // CSRF tekshirish
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('products/create');
        }
        
        // Validatsiya
        $rules = [
            'nomi' => 'required|min:2|max:160',
            'shtrix_kod' => 'required|max:80',
            'kategoriya_id' => 'required|numeric',
            'kelish_narxi' => 'required|numeric',
            'sotish_narxi' => 'required|numeric',
            'birlik' => 'required|max:30'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('products/create');
        }
        
        // Shtrix kod unikal ekanligini tekshirish
        $existing = $this->productModel->findByBarcode($_POST['shtrix_kod']);
        if ($existing) {
            $_SESSION['flash']['error'] = 'Bu shtrix kod allaqachon mavjud';
            $_SESSION['old'] = $_POST;
            $this->redirect('products/create');
        }
        
        // Ma'lumotlarni tayyorlash
        $data = [
            'nomi' => $_POST['nomi'],
            'shtrix_kod' => $_POST['shtrix_kod'],
            'kategoriya_id' => $_POST['kategoriya_id'],
            'subkategoriya_id' => !empty($_POST['subkategoriya_id']) ? $_POST['subkategoriya_id'] : null,
            'birlik' => $_POST['birlik'],
            'kelish_narxi' => str_replace(',', '', $_POST['kelish_narxi']),
            'sotish_narxi' => str_replace(',', '', $_POST['sotish_narxi']),
            'miqdor' => $_POST['miqdor'] ?? 0,
            'minimal_miqdor' => $_POST['minimal_miqdor'] ?? 0,
            'faol' => isset($_POST['faol']) ? 1 : 0
        ];
        
        try {
            $id = $this->productModel->create($data);
            
            // Agar miqdor kiritilgan bo'lsa, ombor jurnaliga yozish
            if ($data['miqdor'] > 0 && isset($_SESSION['user_id'])) {
                $this->productModel->updateStock(
                    $id, 
                    $data['miqdor'], 
                    $_SESSION['user_id'], 
                    'Yangi mahsulot qo\'shildi',
                    'KIRIM'
                );
            }
            
            $_SESSION['flash']['success'] = 'Mahsulot muvaffaqiyatli qo\'shildi';
            $this->redirect('products');
            
        } catch (\Exception $e) {
            error_log("Product store error: " . $e->getMessage());
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
            $_SESSION['old'] = $_POST;
            $this->redirect('products/create');
        }
    }
    
    /**
     * Mahsulotni tahrirlash formasi
     */
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        // Faqat admin va omborchi tahrirlay oladi
        if (!in_array($_SESSION['user']['rol_nomi'], ['Admin', 'Omborchi'])) {
            $_SESSION['flash']['error'] = 'Sizda mahsulot tahrirlash ruxsati yo\'q';
            $this->redirect('products');
        }
        
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $_SESSION['flash']['error'] = 'Mahsulot topilmadi';
            $this->redirect('products');
        }
        
        $categories = $this->categoryModel->all();
        $subcategories = $this->subcategoryModel->where(['kategoriya_id' => $product['kategoriya_id']]);
        
        $this->view('products/edit', [
            'product' => $product,
            'categories' => $categories,
            'subcategories' => $subcategories
        ]);
    }
    
    /**
     * Mahsulotni yangilash
     */
    public function update($id) {
        // CSRF tekshirish
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect("products/edit/$id");
        }
        
        // Validatsiya
        $rules = [
            'nomi' => 'required|min:2|max:160',
            'shtrix_kod' => 'required|max:80',
            'kategoriya_id' => 'required|numeric',
            'kelish_narxi' => 'required|numeric',
            'sotish_narxi' => 'required|numeric',
            'birlik' => 'required|max:30'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect("products/edit/$id");
        }
        
        // Shtrix kod unikal ekanligini tekshirish (o'zidan boshqa)
        $existing = $this->productModel->findByBarcode($_POST['shtrix_kod']);
        if ($existing && $existing['id'] != $id) {
            $_SESSION['flash']['error'] = 'Bu shtrix kod allaqachon mavjud';
            $this->redirect("products/edit/$id");
        }
        
        $data = [
            'nomi' => $_POST['nomi'],
            'shtrix_kod' => $_POST['shtrix_kod'],
            'kategoriya_id' => $_POST['kategoriya_id'],
            'subkategoriya_id' => !empty($_POST['subkategoriya_id']) ? $_POST['subkategoriya_id'] : null,
            'birlik' => $_POST['birlik'],
            'kelish_narxi' => str_replace(',', '', $_POST['kelish_narxi']),
            'sotish_narxi' => str_replace(',', '', $_POST['sotish_narxi']),
            'minimal_miqdor' => $_POST['minimal_miqdor'] ?? 0,
            'faol' => isset($_POST['faol']) ? 1 : 0
        ];
        
        if ($this->productModel->update($id, $data)) {
            $_SESSION['flash']['success'] = 'Mahsulot yangilandi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('products');
    }
    
    /**
     * Mahsulotni o'chirish (soft delete)
     */
    public function delete($id) {
        // CSRF tekshirish
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('products');
        }
        
        // Faqat admin o'chira oladi
        if ($_SESSION['user']['rol_nomi'] !== 'Admin') {
            $_SESSION['flash']['error'] = 'Sizda mahsulot o\'chirish ruxsati yo\'q';
            $this->redirect('products');
        }
        
        // Savdo tarkibida borligini tekshirish
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM savdo_tarkibi WHERE mahsulot_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetch()['count'];
        
        if ($count > 0) {
            $_SESSION['flash']['error'] = 'Bu mahsulot savdolarda ishlatilgan, o\'chirib bo\'lmaydi';
            $this->redirect('products');
        }
        
        if ($this->productModel->delete($id)) {
            $_SESSION['flash']['success'] = 'Mahsulot o\'chirildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('products');
    }
    
    /**
     * Mahsulot detallari (AJAX uchun)
     */
    public function details($id) {
        $product = $this->productModel->find($id);
        
        if (!$product) {
            $this->json(['error' => 'Mahsulot topilmadi'], 404);
        }
        
        // Qo'shimcha ma'lumotlar
        $category = $this->categoryModel->find($product['kategoriya_id']);
        $stats = $this->productModel->getStats($id);
        $history = $this->productModel->getStockHistory($id, 10);
        
        $this->json([
            'product' => $product,
            'category' => $category,
            'stats' => $stats,
            'history' => $history
        ]);
    }
    
    /**
     * Miqdorni tuzatish
     */
    public function adjustStock() {
        // CSRF tekshirish
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('products');
        }
        
        $rules = [
            'product_id' => 'required|numeric',
            'new_quantity' => 'required|numeric',
            'reason' => 'required|max:255'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('products');
        }
        
        $productId = $_POST['product_id'];
        $newQuantity = $_POST['new_quantity'];
        $reason = $_POST['reason'];
        
        if ($this->productModel->updateStock(
            $productId, 
            $newQuantity, 
            $_SESSION['user_id'], 
            $reason,
            'SOZLASH'
        )) {
            $_SESSION['flash']['success'] = 'Mahsulot miqdori tuzatildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('products');
    }
    
    /**
     * Narxni tuzatish
     */
    public function adjustPrice() {
        // CSRF tekshirish
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('products');
        }
        
        $rules = [
            'product_id' => 'required|numeric',
            'new_price' => 'required|numeric',
            'reason' => 'required|max:255'
        ];
        
        if (!$this->validate($_POST, $rules)) {
            $this->redirect('products');
        }
        
        $productId = $_POST['product_id'];
        $newPrice = str_replace(',', '', $_POST['new_price']);
        
        if ($this->productModel->updatePrice($productId, $newPrice, $_SESSION['user_id'])) {
            $_SESSION['flash']['success'] = 'Mahsulot narxi tuzatildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('products');
    }
}