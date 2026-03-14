<?php
// app/Controllers/PosController.php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Pos;
use App\Models\Customer;
use App\Models\SavdoSlot;

class PosController extends Controller
{
    private $productModel;
    private $posModel;
    private $customerModel;
    private $slotModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->posModel = new Pos();
        $this->customerModel = new Customer();
        $this->slotModel = new SavdoSlot();
    }

    /**
     * Normalize quantity for units: integers for "dona" and up to 3 decimals for kg/litr.
     */
    private function normalizeQuantity($quantity, $unit = null)
    {
        $quantity = floatval($quantity);
        if ($quantity <= 0) {
            return 0;
        }

        $unit = strtolower(trim((string)$unit));
        // Dona (pcs) should be whole numbers
        if (in_array($unit, ['dona', 'piece', 'pcs', 'шт'], true)) {
            return (int) round($quantity);
        }

        // Default: allow up to 3 decimal places (kilogram, litr, etc.)
        return round($quantity, 3);
    }

    /**
     * POS asosiy sahifasi
     */
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (!in_array($_SESSION['user']['rol_nomi'], ['Admin', 'Kassir'])) {
            $_SESSION['flash']['error'] = 'Sizda POS ga kirish ruxsati yoʻq';
            $this->redirect('dashboard');
        }

        $smena = $this->posModel->getCurrentSmena($_SESSION['user_id']);
        $categories = $this->db->query("SELECT * FROM kategoriyalar WHERE faol = 1 ORDER BY tartib")->fetchAll();
        $products = $this->productModel->all();
        $customers = $this->customerModel->all();

        // Slotlarni yuklash (agar slot tizimi ishlatilsa)
        $slots = [];
        if (isset($this->slotModel)) {
            $slotsData = $this->slotModel->getActiveSlots($_SESSION['user_id']);
            foreach ($slotsData as $slot) {
                $slot['items'] = $this->slotModel->getSlotItems($slot['id']);
                $slots[] = $slot;
            }
            if (empty($slots)) {
                $slotId = $this->slotModel->createSlot($_SESSION['user_id']);
                $slots = $this->slotModel->getActiveSlots($_SESSION['user_id']);
                foreach ($slots as &$slot) {
                    $slot['items'] = [];
                }
                unset($slot);
            }
        }

        // Eski sessiyadagi savatni ham viewga yuborish (agar slot ishlatilmasa)
        $cart = $_SESSION['cart'] ?? [];

        $this->view('pos/index', [
            'smena' => $smena,
            'categories' => $categories,
            'products' => $products,
            'customers' => $customers,
            'slots' => $slots,
            'cart' => $cart,               // qo‘shimcha, viewda ishlatilishi mumkin
        ]);
    }

    /**
     * Mahsulotlarni AJAX orqali qidirish
     */
    public function searchProducts()
    {
        $keyword = $_GET['q'] ?? '';
        if (empty($keyword)) {
            $this->json([]);
        }
        $products = $this->productModel->search($keyword, 20);
        $this->json($products);
    }

    // ==================== SAVAT (session cart) METODLARI ====================

    /**
     * Mahsulotni savatga qo'shish (AJAX)
     */
    public function addToCart()
    {
        // CSRF tekshirish
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $this->json(['error' => 'CSRF token xato'], 400);
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $rawQuantity = $_POST['quantity'] ?? 1;

        $product = $this->productModel->find($productId);
        if (!$product) {
            $this->json(['error' => 'Mahsulot topilmadi'], 404);
        }

        $quantity = $this->normalizeQuantity($rawQuantity, $product['birlik'] ?? null);

        if ($quantity <= 0) {
            $this->json(['error' => 'Miqdor 0 dan katta bo\'lishi kerak'], 400);
        }

        // Dona (integer) mahsulotlar uchun butun son bo'lishi kerak
        if (in_array(strtolower($product['birlik']), ['dona', 'piece', 'pcs', 'шт'], true) && (float)$quantity != (int)$quantity) {
            $this->json(['error' => 'Dona birlikdagi mahsulot uchun butun miqdor kiriting'], 400);
        }

        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $newQty = $this->normalizeQuantity($item['quantity'] + $quantity, $product['birlik'] ?? null);
                if ((float)$product['miqdor'] < $newQty) {
                    $this->json(['error' => 'Mahsulot yetarli emas. Qoldiq: ' . number_format($product['miqdor'], 3)], 400);
                }
                $item['quantity'] = $newQty;
                $item['total'] = $item['price'] * $item['quantity'];
                $found = true;
                break;
            }
        }
        unset($item); // muhim

        if (!$found) {
            if ((float)$product['miqdor'] < $quantity) {
                $this->json(['error' => 'Mahsulot yetarli emas. Qoldiq: ' . number_format($product['miqdor'], 3)], 400);
            }
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'barcode' => $product['shtrix_kod'],
                'name' => $product['nomi'],
                'price' => $product['sotish_narxi'],
                'quantity' => $quantity,
                'total' => $product['sotish_narxi'] * $quantity,
                'stock' => $product['miqdor'],
                'unit' => $product['birlik']
            ];
        }

        // Yangilangan savatni JSON qaytarish
        $total = array_sum(array_column($_SESSION['cart'], 'total'));
        $this->json([
            'success' => true,
            'items' => $_SESSION['cart'],
            'total' => $total
        ]);
    }

    /**
     * Savatdan mahsulot o'chirish (AJAX / POST)
     */
    public function removeFromCart()
    {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $this->json(['error' => 'CSRF token xato'], 400);
        }

        $productId = (int)($_POST['product_id'] ?? 0);

        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $productId) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']); // indekslarni tozalash
        }

        $cart = $_SESSION['cart'] ?? [];
        $total = array_sum(array_column($cart, 'total'));
        $this->json([
            'success' => true,
            'items' => $cart,
            'total' => $total
        ]);
    }

    /**
     * Savatni tozalash (AJAX / POST)
     */
    public function clearCart()
    {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $this->json(['error' => 'CSRF token xato'], 400);
        }

        unset($_SESSION['cart']);
        $this->json([
            'success' => true,
            'items' => [],
            'total' => 0
        ]);
    }

    /**
     * Savatdagi mahsulot miqdorini o'zgartirish (AJAX)
     */
    public function updateCart()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['csrf_token']) || !validate_csrf($input['csrf_token'])) {
            $this->json(['error' => 'CSRF token xato'], 400);
        }

        $productId = (int)($input['product_id'] ?? 0);
        $rawQuantity = $input['quantity'] ?? 1;

        $product = $this->productModel->find($productId);
        if (!$product) {
            $this->json(['error' => 'Mahsulot topilmadi'], 404);
        }

        $quantity = $this->normalizeQuantity($rawQuantity, $product['birlik'] ?? null);

        // Noto'g'ri miqdor
        if ($quantity < 0) {
            $this->json(['error' => 'Miqdor manfiy bo‘lishi mumkin emas'], 400);
        }

        // 0 kiritilgan: savatdan o'chirish
        if ($quantity == 0) {
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['id'] == $productId) {
                        unset($_SESSION['cart'][$key]);
                        break;
                    }
                }
                $_SESSION['cart'] = array_values($_SESSION['cart'] ?? []);
            }

            $total = array_sum(array_column($_SESSION['cart'] ?? [], 'total'));
            $this->json(['success' => true, 'items' => $_SESSION['cart'] ?? [], 'total' => $total]);
        }

        // Dona mahsulot uchun butun son talab qilinadi
        if (in_array(strtolower($product['birlik']), ['dona', 'piece', 'pcs', 'шт'], true) && (float)$quantity != (int)$quantity) {
            $this->json(['error' => 'Dona birlikdagi mahsulot uchun butun miqdor kiriting'], 400);
        }

        if ((float)$product['miqdor'] < $quantity) {
            $this->json(['error' => 'Mahsulot yetarli emas. Qoldiq: ' . number_format($product['miqdor'], 3)]);
        }

        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $productId) {
                    $item['quantity'] = $quantity;
                    $item['total'] = $item['price'] * $quantity;
                    break;
                }
            }
            unset($item);
        }

        $total = array_sum(array_column($_SESSION['cart'] ?? [], 'total'));
        $this->json([
            'success' => true,
            'items' => $_SESSION['cart'] ?? [],
            'total' => $total
        ]);
    }

    /**
     * Savatni ko'rish (AJAX)
     */
    public function viewCart()
    {
        $cart = $_SESSION['cart'] ?? [];
        $total = array_sum(array_column($cart, 'total'));
        $this->json([
            'items' => $cart,
            'total' => $total
        ]);
    }

    // ==================== SAVDONI YAKUNLASH ====================

    /**
     * Savdoni yakunlash (chegirma bilan)
     */
    public function checkout()
    {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('pos');
        }

        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            $_SESSION['flash']['error'] = 'Savat boʻsh';
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

        $subtotal = array_sum(array_column($cart, 'total'));

        $discountAmount = 0;
        if ($discountType == 'percent' && $discountValue > 0) {
            $discountAmount = ($subtotal * $discountValue) / 100;
        } elseif ($discountType == 'fixed' && $discountValue > 0) {
            $discountAmount = $discountValue;
        }
        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }
        $total = $subtotal - $discountAmount;

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

        // Validatsiya
        if ($debt > 0 && !$customerId) {
            $_SESSION['flash']['error'] = 'Nasiya yoki qisman toʻlov uchun mijoz tanlashingiz kerak';
            $_SESSION['old'] = $_POST;
            $this->redirect('pos');
        }
        if ($paidAmount < 0) {
            $_SESSION['flash']['error'] = 'Toʻlangan summa manfiy boʻlishi mumkin emas';
            $this->redirect('pos');
        }

        // Mijoz qarzini tekshirish (ogohlantirish)
        if ($customerId && $debt > 0) {
            try {
                $debtModel = new \App\Models\Debt();
                $customerDebt = $debtModel->getCustomerDebt($customerId);
                if ($customerDebt && $customerDebt['jami_qarz'] > 0) {
                    $_SESSION['warning'] = 'Diqqat! Mijozning eski qarzi bor: ' . number_format($customerDebt['jami_qarz'], 0, ',', ' ') . ' soʻm';
                }
            } catch (\Exception $e) {
                error_log("Debt check error: " . $e->getMessage());
            }
        }

        // Chek raqami yaratish
        try {
            $chekRaqami = $this->posModel->generateChekRaqami();
        } catch (\Exception $e) {
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
            $product = $this->productModel->find($item['id']);
            if (!$product) {
                $_SESSION['flash']['error'] = 'Mahsulot topilmadi: ' . $item['name'];
                $this->redirect('pos');
            }
            if ($product['miqdor'] < $item['quantity']) {
                $_SESSION['flash']['error'] = 'Mahsulot yetarli emas: ' . $item['name'] . '. Qoldiq: ' . $product['miqdor'];
                $this->redirect('pos');
            }
            $items[] = [
                'mahsulot_id' => $item['id'],
                'soni' => $item['quantity'],
                'birlik_narx' => $item['price'],
                'chegirma' => 0,
                'qator_summa' => $item['total']
            ];
        }

        // Savdoni saqlash (3 marta urinish)
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
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $saleData['chek_raqami'] = 'CHK-' . date('Ymd') . '-' . time() . '-' . rand(100, 999) . '-' . $attempt;
                    error_log("Duplicate entry, retrying with new chek raqami: " . $saleData['chek_raqami']);
                } else {
                    break;
                }
            }
        }

        if ($savdoId) {
            unset($_SESSION['cart']); // savatni tozalash
            $successMessage = 'Savdo muvaffaqiyatli amalga oshirildi. Chek raqami: ' . $saleData['chek_raqami'];
            if ($debt > 0) {
                $successMessage .= '. Qarz: ' . number_format($debt, 0, ',', ' ') . ' soʻm';
            }
            $_SESSION['flash']['success'] = $successMessage;
            $_SESSION['auto_print'] = true; // avtomatik chop etish uchun
            $this->redirect('pos/receipt/' . $savdoId);
        } else {
            error_log("Checkout failed after $attempt attempts. Last error: " . ($lastError ? $lastError->getMessage() : 'Unknown error'));
            $errorMessage = $lastError ? $lastError->getMessage() : 'Nomaʻlum xatolik';
            if (strpos($errorMessage, 'Duplicate entry') !== false) {
                $errorMessage = 'Texnik xatolik yuz berdi. Iltimos, qaytadan urinib koʻring.';
            } elseif (strpos($errorMessage, 'foreign key') !== false) {
                $errorMessage = 'Maʻlumotlar bogʻliqligi xatoligi.';
            } elseif (strpos($errorMessage, 'null') !== false) {
                $errorMessage = 'Majburiy maʻlumotlar toʻldirilmagan.';
            }
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $errorMessage;
            $this->redirect('pos');
        }
    }

    // ==================== CHEK ====================

    /**
     * Chekni ko'rish
     */
    public function receipt($id)
    {
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

    // ==================== SMENA ====================

    /**
     * Smena ochish
     */
    public function openShift()
    {
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
    public function closeShift()
    {
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
        
        // Smena summary olish
        $summary = $this->posModel->getSmenaSummary($smena['id']);
        $expected = $summary['expected_cash'] ?? 0;
        $difference = $naqd - $expected;

        if ($this->posModel->closeSmena($smena['id'], $naqd)) {
            $_SESSION['flash']['success'] = sprintf(
                'Smena yopildi. Boshlang\'ich: %s, Naqd savdo: %s, Qaytarish: %s, Diller to\'lovlari: %s, Kutilgan: %s, Haqiqiy: %s, Farq: %s',
                number_format($summary['ochilish_naqd'], 2),
                number_format($summary['jami_naqd_tolov'] ?? 0, 2),
                number_format($summary['qaytarilgan_summa'], 2),
                number_format($summary['diller_tolovlari'], 2),
                number_format($expected, 2),
                number_format($naqd, 2),
                number_format($difference, 2)
            );
        } else {
            $_SESSION['flash']['error'] = 'Smena yopishda xatolik';
        }
        $this->redirect('pos');
    }

    // ==================== SLOT TIZIMI (agar ishlatilsa) ====================

    /**
     * Slotlarni yuklash
     */
    public function loadSlots()
    {
        $slots = $this->slotModel->getActiveSlots($_SESSION['user_id']);
        if (empty($slots)) {
            $slotId = $this->slotModel->createSlot($_SESSION['user_id']);
            $slots = $this->slotModel->getActiveSlots($_SESSION['user_id']);
        }
        return $slots;
    }

    /**
     * Yangi slot yaratish (AJAX)
     */
    public function createSlot()
    {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $this->json(['error' => 'CSRF token xato']);
        }
        $slotId = $this->slotModel->createSlot($_SESSION['user_id']);
        if ($slotId) {
            $this->json(['success' => true, 'slot_id' => $slotId]);
        } else {
            $this->json(['error' => 'Slot yaratishda xatolik']);
        }
    }

    /**
     * Slotga mahsulot qo'shish
     */
    public function addToSlot()
    {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('pos');
        }

        $slotId = (int)($_POST['slot_id'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = floatval($_POST['quantity'] ?? 1);
        $quantity = $this->normalizeQuantity($quantity, $product['birlik'] ?? null);

        $product = $this->productModel->find($productId);
        if (!$product) {
            $_SESSION['flash']['error'] = 'Mahsulot topilmadi';
            $this->redirect('pos');
        }
        // Dona mahsulotlar uchun butun miqdor talab qilinadi
        if (in_array(strtolower($product['birlik']), ['dona', 'piece', 'pcs', 'шт'], true) && (float)$quantity != (int)$quantity) {
            $_SESSION['flash']['error'] = 'Dona birlikdagi mahsulot uchun butun miqdor kiriting';
            $this->redirect('pos');
        }

        if ((float)$product['miqdor'] < $quantity) {
            $_SESSION['flash']['error'] = 'Mahsulot yetarli emas. Qoldiq: ' . number_format($product['miqdor'], 3);
            $this->redirect('pos');
        }

        if ($this->slotModel->addProduct($slotId, $productId, $quantity)) {
            $_SESSION['flash']['success'] = 'Mahsulot qoʻshildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        $this->redirect('pos');
    }

    /**
     * Slotdan mahsulot o'chirish
     */
    public function removeFromSlot()
    {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('pos');
        }

        $slotId = (int)($_POST['slot_id'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? 0);

        if ($this->slotModel->removeProduct($slotId, $productId)) {
            $_SESSION['flash']['success'] = 'Mahsulot oʻchirildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        $this->redirect('pos');
    }

    /**
     * Slotdagi mahsulot sonini yangilash (AJAX)
     */
    public function updateSlotQuantity()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['csrf_token']) || !validate_csrf($input['csrf_token'])) {
            $this->json(['error' => 'CSRF token xato']);
        }

        $slotId = (int)($input['slot_id'] ?? 0);
        $productId = (int)($input['product_id'] ?? 0);
        $quantity = floatval($input['quantity'] ?? 1);

        $product = $this->productModel->find($productId);
        $quantity = $this->normalizeQuantity($quantity, $product['birlik'] ?? null);

        $product = $this->productModel->find($productId);
        if (!$product) {
            $this->json(['error' => 'Mahsulot topilmadi']);
        }
        // Dona mahsulotlar uchun butun miqdor talab qilinadi
        if (in_array(strtolower($product['birlik']), ['dona', 'piece', 'pcs', 'шт'], true) && (float)$quantity != (int)$quantity) {
            $this->json(['error' => 'Dona birlikdagi mahsulot uchun butun miqdor kiriting']);
        }

        if ((float)$product['miqdor'] < $quantity) {
            $this->json(['error' => 'Mahsulot yetarli emas. Qoldiq: ' . number_format($product['miqdor'], 3)]);
        }

        if ($this->slotModel->updateQuantity($slotId, $productId, $quantity)) {
            $items = $this->slotModel->getSlotItems($slotId);
            $total = $this->slotModel->updateSlotTotal($slotId);
            $this->json(['success' => true, 'items' => $items, 'total' => $total]);
        } else {
            $this->json(['error' => 'Xatolik yuz berdi']);
        }
    }

    /**
     * Slotni to'xtatish (hold)
     */
    public function holdSlot()
    {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $this->json(['error' => 'CSRF token xato']);
        }
        $slotId = (int)($_POST['slot_id'] ?? 0);
        if ($this->slotModel->updateStatus($slotId, 'kutilmoqda')) {
            $_SESSION['flash']['success'] = 'Slot toʻxtatildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        $this->redirect('pos');
    }

    /**
     * Slotni faollashtirish
     */
    public function activateSlot()
    {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $this->json(['error' => 'CSRF token xato']);
        }
        $slotId = (int)($_POST['slot_id'] ?? 0);
        if ($this->slotModel->updateStatus($slotId, 'aktiv')) {
            $_SESSION['flash']['success'] = 'Slot faollashtirildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        $this->redirect('pos');
    }

    /**
     * Slot ma'lumotlarini yangilash (mijoz, nom)
     */
    public function updateSlot()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['csrf_token']) || !validate_csrf($input['csrf_token'])) {
            $this->json(['error' => 'CSRF token xato']);
        }

        $slotId = (int)($input['slot_id'] ?? 0);
        $data = [];
        if (isset($input['nom'])) {
            $data['nom'] = $input['nom'];
        }
        if (isset($input['mijoz_id'])) {
            $data['mijoz_id'] = $input['mijoz_id'] ?: null;
        }

        if ($this->slotModel->updateSlot($slotId, $data)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['error' => 'Xatolik yuz berdi']);
        }
    }

    /**
     * Slotni yakunlash (savdoga aylantirish)
     */
    public function checkoutSlot()
    {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('pos');
        }

        $slotId = (int)($_POST['slot_id'] ?? 0);
        $paymentMethod = $_POST['payment_method'] ?? 'NAQD';
        $paidAmount = floatval(str_replace(',', '', $_POST['paid_amount'] ?? '0'));
        $customerId = !empty($_POST['customer_id']) ? $_POST['customer_id'] : null;
        $note = $_POST['note'] ?? '';

        $items = $this->slotModel->getSlotItems($slotId);
        if (empty($items)) {
            $_SESSION['flash']['error'] = 'Slot boʻsh';
            $this->redirect('pos');
        }

        $slots = $this->slotModel->getActiveSlots($_SESSION['user_id']);
        $currentSlot = null;
        foreach ($slots as $slot) {
            if ($slot['id'] == $slotId) {
                $currentSlot = $slot;
                break;
            }
        }
        if (!$currentSlot) {
            $_SESSION['flash']['error'] = 'Slot topilmadi';
            $this->redirect('pos');
        }

        $total = $currentSlot['umumiy_summa'];

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

        if (!$customerId && $debt > 0) {
            $_SESSION['flash']['error'] = 'Nasiya savdo uchun mijoz tanlashingiz kerak';
            $this->redirect('pos');
        }

        $tolovMalumotlari = [
            'usul' => $paymentMethod,
            'holat' => $paymentStatus,
            'tolangan' => $paidAmount,
            'qarz' => $debt,
            'chegirma' => 0,
            'izoh' => $note
        ];

        try {
            $savdoId = $this->slotModel->completeSlot($slotId, $tolovMalumotlari);
            $_SESSION['flash']['success'] = 'Savdo muvaffaqiyatli amalga oshirildi';
            $this->redirect('pos/receipt/' . $savdoId);
        } catch (\Exception $e) {
            error_log("Checkout slot error: " . $e->getMessage());
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
            $this->redirect('pos');
        }
    }
}