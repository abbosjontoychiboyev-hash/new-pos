<?php
// app/Controllers/PosController.php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Pos;
use App\Models\Customer;

class PosController extends Controller {
    
    private $productModel;
    private $posModel;
    private $customerModel;
    
    public function __construct() {
        parent::__construct();
        $this->productModel = new Product();
        $this->posModel = new Pos();
        $this->customerModel = new Customer();
    }
    
    /**
     * POS asosiy sahifasi
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        // Kassir rolini tekshirish
        if (!in_array($_SESSION['user']['rol_nomi'], ['Admin', 'Kassir'])) {
            $_SESSION['flash']['error'] = 'Sizda POS ga kirish ruxsati yo\'q';
            $this->redirect('dashboard');
        }
        
        // Joriy smenani tekshirish
        $smena = $this->posModel->getCurrentSmena($_SESSION['user_id']);
        
        // Kategoriyalarni olish
        $categories = $this->db->query("SELECT * FROM kategoriyalar WHERE faol = 1 ORDER BY tartib")->fetchAll();
        
        // Mahsulotlarni olish
        $products = $this->productModel->all();
        
        // Mijozlarni olish
        $customers = $this->customerModel->all();
        
        // Savatni sessiyadan olish
        $cart = $_SESSION['cart'] ?? [];
        
        // Savatdagi mahsulotlar uchun qo'shimcha ma'lumot
        foreach ($cart as &$item) {
            $product = $this->productModel->find($item['id']);
            $item['stock'] = $product['miqdor'] ?? 0;
        }
        
        $this->view('pos/index', [
            'smena' => $smena,
            'categories' => $categories,
            'products' => $products,
            'customers' => $customers,
            'cart' => $cart
        ]);
    }
    
    /**
     * Mahsulotlarni AJAX orqali qidirish
     */
    public function searchProducts() {
        $keyword = $_GET['q'] ?? '';
        
        if (empty($keyword)) {
            $this->json([]);
        }
        
        $products = $this->productModel->search($keyword, 20);
        $this->json($products);
    }
    
    /**
     * Mahsulotni savatga qo'shish
     */
    public function addToCart() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('pos');
        }
        
        $productId = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        
        $product = $this->productModel->find($productId);
        
        if (!$product) {
            $_SESSION['flash']['error'] = 'Mahsulot topilmadi';
            $this->redirect('pos');
        }
        
        if ($product['miqdor'] < $quantity) {
            $_SESSION['flash']['error'] = 'Mahsulot yetarli emas. Qoldiq: ' . $product['miqdor'];
            $this->redirect('pos');
        }
        
        // Savatni sessiyaga olish
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Mahsulot savatda borligini tekshirish
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] += $quantity;
                $item['total'] = $item['price'] * $item['quantity'];
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'barcode' => $product['shtrix_kod'],
                'name' => $product['nomi'],
                'price' => $product['sotish_narxi'],
                'quantity' => $quantity,
                'total' => $product['sotish_narxi'] * $quantity,
                'stock' => $product['miqdor']
            ];
        }
        
        $this->redirect('pos');
    }
    
    /**
     * Savatdan mahsulot o'chirish
     */
    public function removeFromCart() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('pos');
        }
        
        $productId = $_POST['product_id'] ?? 0;
        
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $productId) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            // Indekslarni qayta tartiblash
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
        
        $this->redirect('pos');
    }
    
    /**
     * Savatni tozalash
     */
    public function clearCart() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('pos');
        }
        
        unset($_SESSION['cart']);
        $this->redirect('pos');
    }
    
    /**
     * Savatdagi mahsulot miqdorini o'zgartirish
     */
    public function updateCart() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $productId = $input['product_id'] ?? 0;
        $quantity = $input['quantity'] ?? 1;
        
        $product = $this->productModel->find($productId);
        
        if (!$product) {
            $this->json(['error' => 'Mahsulot topilmadi']);
        }
        
        if ($product['miqdor'] < $quantity) {
            $this->json(['error' => 'Mahsulot yetarli emas. Qoldiq: ' . $product['miqdor']]);
        }
        
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $productId) {
                    $item['quantity'] = $quantity;
                    $item['total'] = $item['price'] * $quantity;
                    break;
                }
            }
        }
        
        $this->json(['success' => true, 'cart' => $_SESSION['cart']]);
    }
    
    /**
     * Savatni ko'rish (AJAX)
     */
    public function viewCart() {
        $cart = $_SESSION['cart'] ?? [];
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['total'];
        }
        
        $this->json([
            'items' => $cart,
            'total' => $total,
            'count' => count($cart)
        ]);
    }
    
   /**
     * Savdoni yakunlash (chegirma bilan)
     */
    public function checkout() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('pos');
        }
        
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            $_SESSION['flash']['error'] = 'Savat bo\'sh';
            $this->redirect('pos');
        }
        
        // To'lov ma'lumotlari
        $paymentMethod = $_POST['payment_method'] ?? 'NAQD';
        $paidAmount = floatval(str_replace(',', '', $_POST['paid_amount'] ?? '0'));
        $customerId = !empty($_POST['customer_id']) ? $_POST['customer_id'] : null;
        $note = $_POST['note'] ?? '';
        
        // Chegirma
        $discountType = $_POST['discount_type'] ?? 'fixed';
        $discountValue = floatval(str_replace(',', '', $_POST['discount_value'] ?? '0'));
        
        // Umumiy summani hisoblash
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['total'];
        }
        
        // Chegirmani hisoblash
        $discountAmount = 0;
        if ($discountType == 'percent' && $discountValue > 0) {
            $discountAmount = ($subtotal * $discountValue) / 100;
        } elseif ($discountType == 'fixed' && $discountValue > 0) {
            $discountAmount = $discountValue;
        }
        
        // Chegirma summasi subtotal dan oshib ketmasligi kerak
        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }
        
        $total = $subtotal - $discountAmount;
        
        // To'lov holati va qarzni aniqlash
        if ($paidAmount >= $total) {
            $paymentStatus = 'TOLANGAN';
            $debt = 0;
        } elseif ($paidAmount > 0) {
            $paymentStatus = 'QISMAN';
            $debt = $total - $paidAmount;
        } else {
            $paymentStatus = 'NASIYA';
            $debt = $total;
        }
        
        // VALIDATSIYA: Nasiya yoki qisman to'lov uchun mijoz tanlangan bo'lishi kerak
        if ($debt > 0 && !$customerId) {
            $_SESSION['flash']['error'] = 'Nasiya yoki qisman to\'lov uchun mijoz tanlashingiz kerak';
            $_SESSION['old'] = $_POST;
            $this->redirect('pos');
        }
        
        // VALIDATSIYA: To'langan summa manfiy bo'lmasligi kerak
        if ($paidAmount < 0) {
            $_SESSION['flash']['error'] = 'To\'langan summa manfiy bo\'lishi mumkin emas';
            $this->redirect('pos');
        }
        
        // Agar mijoz tanlangan bo'lsa, uning eski qarzlarini tekshirish
        if ($customerId && $debt > 0) {
            try {
                $debtModel = new \App\Models\Debt();
                $customerDebt = $debtModel->getCustomerDebt($customerId);
                
                if ($customerDebt && $customerDebt['jami_qarz'] > 0) {
                    // Eski qarz borligi haqida ogohlantirish (xatolik emas, faqat ogohlantirish)
                    $_SESSION['warning'] = 'Diqqat! Mijozning eski qarzi bor: ' . number_format($customerDebt['jami_qarz'], 0, ',', ' ') . ' so\'m';
                }
            } catch (\Exception $e) {
                // Debt model yo'q bo'lishi mumkin, xatolikni logga yozib, davom etish
                error_log("Debt check error: " . $e->getMessage());
            }
        }
        
        // Chek raqamini yaratish
        try {
            $chekRaqami = $this->posModel->generateChekRaqami();
        } catch (\Exception $e) {
            // Agar generate qilishda xatolik bo'lsa, oddiy raqam yaratish
            $chekRaqami = 'CHK-' . date('Ymd') . '-' . time() . '-' . rand(100, 999);
            error_log("Chek raqami generation error: " . $e->getMessage());
        }
        
        // Savdo ma'lumotlari
        $saleData = [
            'chek_raqami' => $chekRaqami,
            'mijoz_id' => $customerId,
            'tolov_usuli' => $paymentMethod,
            'tolov_holati' => $paymentStatus,
            'umumiy_summa' => $subtotal,
            'chegirma_summa' => $discountAmount,
            'yakuniy_summa' => $total,
            'tolangan_summa' => $paidAmount,
            'qarz_summa' => $debt,
            'holat' => 'YAKUNLANGAN',
            'izoh' => $note
        ];
        
        // Savdo tarkibi
        $items = [];
        foreach ($cart as $item) {
            // Mahsulot mavjudligini tekshirish
            $product = $this->productModel->find($item['id']);
            if (!$product) {
                $_SESSION['flash']['error'] = 'Mahsulot topilmadi: ' . $item['name'];
                $this->redirect('pos');
            }
            
            // Mahsulot yetarliligini tekshirish
            if ($product['miqdor'] < $item['quantity']) {
                $_SESSION['flash']['error'] = 'Mahsulot yetarli emas: ' . $item['name'] . '. Qoldiq: ' . $product['miqdor'];
                $this->redirect('pos');
            }
            
            $items[] = [
                'mahsulot_id' => $item['id'],
                'soni' => $item['quantity'],
                'birlik_narx' => $item['price'],
                'chegirma' => 0, // Har bir item uchun alohida chegirma qilish mumkin
                'qator_summa' => $item['total']
            ];
        }
        
        // Savdoni saqlashga urinish
        $maxAttempts = 3;
        $attempt = 0;
        $savdoId = null;
        $lastError = null;
        
        while ($attempt < $maxAttempts && !$savdoId) {
            try {
                $savdoId = $this->posModel->saveSale($saleData, $items, $_SESSION['user_id']);
                
            } catch (\Exception $e) {
                $lastError = $e;
                $attempt++;
                
                // Duplicate entry xatoligi bo'lsa, yangi chek raqami bilan urinish
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    // Yangi chek raqami yaratish
                    $saleData['chek_raqami'] = 'CHK-' . date('Ymd') . '-' . time() . '-' . rand(100, 999) . '-' . $attempt;
                    error_log("Duplicate entry, retrying with new chek raqami: " . $saleData['chek_raqami']);
                } else {
                    // Boshqa xatolik bo'lsa, siklni to'xtatish
                    break;
                }
            }
        }
        
        // Natijani tekshirish
        if ($savdoId) {
            // Savatni tozalash
            unset($_SESSION['cart']);
            
            // Muvaffaqiyatli xabar
            $successMessage = 'Savdo muvaffaqiyatli amalga oshirildi. Chek raqami: ' . $saleData['chek_raqami'];
            
            // Agar qarz bo'lsa, qo'shimcha xabar
            if ($debt > 0) {
                $successMessage .= '. Qarz: ' . number_format($debt, 0, ',', ' ') . ' so\'m';
                
                // Agar mijoz tanlangan bo'lsa, qarz sahifasiga link
                if ($customerId) {
                    $_SESSION['flash']['success'] = $successMessage . ' <a href="/new-pos/debt/customer/' . $customerId . '" class="alert-link">Qarzni ko\'rish</a>';
                } else {
                    $_SESSION['flash']['success'] = $successMessage;
                }
            } else {
                $_SESSION['flash']['success'] = $successMessage;
            }
            
            // Chek sahifasiga o'tish
            $this->redirect('pos/receipt/' . $savdoId);
            
        } else {
            // Xatolik yuz berdi
            error_log("Checkout failed after $attempt attempts. Last error: " . ($lastError ? $lastError->getMessage() : 'Unknown error'));
            
            // Xatolik xabarini tayyorlash
            if ($lastError) {
                $errorMessage = $lastError->getMessage();
                
                // Foydalanuvchiga tushunarli qilish
                if (strpos($errorMessage, 'Duplicate entry') !== false) {
                    $errorMessage = 'Texnik xatolik yuz berdi. Iltimos, qaytadan urinib ko\'ring.';
                } elseif (strpos($errorMessage, 'foreign key') !== false) {
                    $errorMessage = 'Ma\'lumotlar bog\'liqligi xatoligi.';
                } elseif (strpos($errorMessage, 'null') !== false) {
                    $errorMessage = 'Majburiy ma\'lumotlar to\'ldirilmagan.';
                }
            } else {
                $errorMessage = 'Noma\'lum xatolik yuz berdi';
            }
            
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $errorMessage;
            $this->redirect('pos');
        }
    }
        /**
         * Chekni ko'rish
         */
        public function receipt($id) {
            // Savdo ma'lumotlarini olish
            $stmt = $this->db->prepare("
                SELECT s.*, u.fio as kassir_fio, m.fio as mijoz_fio, m.telefon as mijoz_tel
                FROM savdolar s
                LEFT JOIN foydalanuvchilar u ON s.kassir_id = u.id
                LEFT JOIN mijozlar m ON s.mijoz_id = m.id
                WHERE s.id = ?
            ");
            $stmt->execute([$id]);
            $sale = $stmt->fetch();
            
            if (!$sale) {
                $_SESSION['flash']['error'] = 'Chek topilmadi';
                $this->redirect('pos');
            }
            
            // Debug: ma'lumotlarni logga yozish
            error_log("Receipt sale data: " . print_r($sale, true));
            
            // Savdo tarkibini olish
            $stmt = $this->db->prepare("
                SELECT st.*, m.nomi, m.shtrix_kod
                FROM savdo_tarkibi st
                LEFT JOIN mahsulotlar m ON st.mahsulot_id = m.id
                WHERE st.savdo_id = ?
            ");
            $stmt->execute([$id]);
            $items = $stmt->fetchAll();
            
            $this->view('pos/receipt', [
                'sale' => $sale,
                'items' => $items
            ]);
        }
    
    /**
     * Smena ochish
     */
    public function openShift() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('pos');
        }
        
        $naqd = floatval(str_replace(',', '', $_POST['opening_cash'] ?? '0'));
        
        if ($this->posModel->openSmena($_SESSION['user_id'], $naqd)) {
            $_SESSION['flash']['success'] = 'Smena muvaffaqiyatli ochildi';
        } else {
            $_SESSION['flash']['error'] = 'Smena ochishda xatolik';
        }
        
        $this->redirect('pos');
    }
    
    /**
     * Smena yopish
     */
    public function closeShift() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('pos');
        }
        
        $smena = $this->posModel->getCurrentSmena($_SESSION['user_id']);
        
        if (!$smena) {
            $_SESSION['flash']['error'] = 'Ochiq smena topilmadi';
            $this->redirect('pos');
        }
        
        $naqd = floatval(str_replace(',', '', $_POST['closing_cash'] ?? '0'));
        
        if ($this->posModel->closeSmena($smena['id'], $naqd)) {
            $_SESSION['flash']['success'] = 'Smena muvaffaqiyatli yopildi';
        } else {
            $_SESSION['flash']['error'] = 'Smena yopishda xatolik';
        }
        
        $this->redirect('pos');
    }
}