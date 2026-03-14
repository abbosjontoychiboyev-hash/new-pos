<?php
namespace App\Controllers;

class ReturnController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Qaytarish asosiy sahifasi
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $this->view('returns/index');
    }
    
    /**
     * Chek raqami bo'yicha qidirish
     */
    public function search() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $receiptNumber = $_GET['receipt'] ?? '';
        
        if (empty($receiptNumber)) {
            $_SESSION['flash']['error'] = 'Chek raqamini kiriting';
            $this->redirect('returns');
        }
        
        // Ma'lumotlar bazasidan chekni qidirish
        try {
            $stmt = $this->db->prepare("
                SELECT s.*, u.fio as kassir_fio, m.fio as mijoz_fio
                FROM savdolar s
                LEFT JOIN foydalanuvchilar u ON s.kassir_id = u.id
                LEFT JOIN mijozlar m ON s.mijoz_id = m.id
                WHERE s.chek_raqami = ? AND s.holat = 'YAKUNLANGAN'
            ");
            $stmt->execute([$receiptNumber]);
            $sale = $stmt->fetch();
            
            if (!$sale) {
                $_SESSION['flash']['error'] = 'Chek topilmadi yoki bekor qilingan';
                $this->redirect('returns');
            }
            
            // Savdo tarkibini olish
            $stmt = $this->db->prepare("
                SELECT 
                    st.*,
                    p.nomi,
                    p.shtrix_kod,
                    p.birlik
                FROM savdo_tarkibi st
                JOIN mahsulotlar p ON st.mahsulot_id = p.id
                WHERE st.savdo_id = ?
                ORDER BY st.id ASC
            ");
            $stmt->execute([$sale['id']]);
            $items = $stmt->fetchAll();
            
            $this->view('returns/details', [
                'sale' => $sale,
                'items' => $items
            ]);
            
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
            $this->redirect('returns');
        }
    }
    
    /**
     * Qaytarishni amalga oshirish
     */
    public function process() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('returns');
        }
        
        $saleId = $_POST['sale_id'] ?? 0;
        $receiptNumber = $_POST['receipt'] ?? '';
        $reason = $_POST['reason'] ?? '';
        
        if (empty($reason)) {
            $_SESSION['flash']['error'] = 'Qaytarish sababini kiriting';
            $this->redirect('returns/search?receipt=' . urlencode($receiptNumber));
        }
        
        $returnItems = $_POST['return_items'] ?? [];
        
        if (empty($returnItems)) {
            $_SESSION['flash']['error'] = 'Qaytariladigan mahsulotlarni tanlang';
            $this->redirect('returns/search?receipt=' . urlencode($receiptNumber));
        }
        
        // Qaytarish jarayoni
        try {
            $this->db->beginTransaction();
            
            $totalReturnAmount = 0;
            
            foreach ($returnItems as $itemId => $quantity) {
                $quantity = (float)$quantity;
                if ($quantity <= 0) continue;
                
                // Item ma'lumotlarini olish
                $stmt = $this->db->prepare("SELECT * FROM savdo_tarkibi WHERE id = ? AND savdo_id = ?");
                $stmt->execute([$itemId, $saleId]);
                $item = $stmt->fetch();
                
                if (!$item || $item['soni'] < $quantity) {
                    throw new \Exception('Noto\'g\'ri miqdor');
                }
                
                $returnAmount = $quantity * $item['birlik_narx'];
                $totalReturnAmount += $returnAmount;
                
                // Qaytarishni saqlash
                $stmt = $this->db->prepare("
                    INSERT INTO qaytarishlar (savdo_id, savdo_tarkibi_id, mahsulot_id, miqdor, summa, sabab, foydalanuvchi_id, qaytarilgan_vaqt)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$saleId, $itemId, $item['mahsulot_id'], $quantity, $returnAmount, $reason, $_SESSION['user_id']]);
                
                // Savdo tarkibini yangilash
                if ($item['soni'] == $quantity) {
                    // To'liq qaytarish
                    $stmt = $this->db->prepare("DELETE FROM savdo_tarkibi WHERE id = ?");
                    $stmt->execute([$itemId]);
                } else {
                    // Qisman qaytarish
                    $stmt = $this->db->prepare("UPDATE savdo_tarkibi SET soni = soni - ?, qator_summa = qator_summa - ? WHERE id = ?");
                    $stmt->execute([$quantity, $returnAmount, $itemId]);
                }
                
                // Omborga qaytarish
                $stmt = $this->db->prepare("UPDATE mahsulotlar SET miqdor = miqdor + ? WHERE id = ?");
                $stmt->execute([$quantity, $item['mahsulot_id']]);
                
                // Warehouse journal
                $stmt = $this->db->prepare("
                    INSERT INTO ombor_jurnali (mahsulot_id, amal, miqdor_ozgarish, eski_miqdor, yangi_miqdor, manba_turi, izoh)
                    SELECT ?, 'KIRIM', ?, miqdor - ?, miqdor, 'QAYTARISH', ? FROM mahsulotlar WHERE id = ?
                ");
                $stmt->execute([$item['mahsulot_id'], $quantity, $quantity, "Qaytarish: $reason", $item['mahsulot_id']]);
            }
            
            // Savdo umumiy summasini yangilash
            $stmt = $this->db->prepare("
                UPDATE savdolar SET 
                    umumiy_summa = (SELECT IFNULL(SUM(qator_summa), 0) FROM savdo_tarkibi WHERE savdo_id = id),
                    yakuniy_summa = umumiy_summa - chegirma_summa,
                    tolangan_summa = LEAST(tolangan_summa, yakuniy_summa),
                    qarz_summa = GREATEST(0, yakuniy_summa - tolangan_summa)
                WHERE id = ?
            ");
            $stmt->execute([$saleId]);
            
            $this->db->commit();
            
            $_SESSION['flash']['success'] = 'Mahsulotlar muvaffaqiyatli qaytarildi. Jami: ' . number_format($totalReturnAmount, 2) . ' so\'m';
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
        }
        
        $this->redirect('returns');
    }
    
    /**
     * Qaytarish tarixi
     */
    public function history() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        // Bekor qilingan savdolarni olish
        $stmt = $this->db->prepare("
            SELECT s.*, u.fio as kassir_fio
            FROM savdolar s
            LEFT JOIN foydalanuvchilar u ON s.kassir_id = u.id
            WHERE s.holat = 'BEKOR' 
                AND DATE(s.sotilgan_vaqt) BETWEEN ? AND ?
            ORDER BY s.sotilgan_vaqt DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        $returns = $stmt->fetchAll();
        
        $this->view('returns/history', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'returns' => $returns
        ]);
    }
}