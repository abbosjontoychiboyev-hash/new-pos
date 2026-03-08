<?php
namespace App\Controllers;

class ApiController extends Controller {
    
    /**
     * Savdo mahsulotlarini olish
     */
    public function saleDetails($id) {
        // Savdo ma'lumotlarini olish
        $stmt = $this->db->prepare("
            SELECT s.*, u.fio as kassir_fio, m.fio as mijoz_fio
            FROM savdolar s
            LEFT JOIN foydalanuvchilar u ON s.kassir_id = u.id
            LEFT JOIN mijozlar m ON s.mijoz_id = m.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $sale = $stmt->fetch();
        
        if (!$sale) {
            $this->json(['error' => 'Savdo topilmadi'], 404);
        }
        
        // Savdo tarkibini olish
        $stmt = $this->db->prepare("
            SELECT 
                st.*,
                p.nomi,
                p.shtrix_kod,
                p.birlik
            FROM savdo_tarkibi st
            LEFT JOIN mahsulotlar p ON st.mahsulot_id = p.id
            WHERE st.savdo_id = ?
            ORDER BY st.id ASC
        ");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll();
        
        // Jami summani hisoblash
        $total = 0;
        foreach ($items as $item) {
            $total += $item['qator_summa'];
        }
        
        $this->json([
            'sale' => $sale,
            'items' => $items,
            'total' => $total
        ]);
    }
    public function getProduct($id) {
        if (!isset($_SESSION['user_id'])) {
            return $this->json(['error' => 'Avval tizimga kiring'], 401);
        }
        $productModel = new Product();
        $product = $productModel->find($id);
        if (!$product) {
            return $this->json(['error' => 'Mahsulot topilmadi'], 404);
        }
        $categoryModel = new Category();
        $category = $categoryModel->find($product['kategoriya_id']);
        $stats = $productModel->getStats($id);
        return $this->json([
            'product' => $product,
            'category' => $category,
            'stats' => $stats
        ]);
    }
}