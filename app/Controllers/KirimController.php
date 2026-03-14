<?php
namespace App\Controllers;

use App\Models\Kirim;
use App\Models\YetkazibBeruvchi;
use App\Models\Product;

class KirimController extends Controller {
    
    private $kirimModel;
    private $yetkazibModel;
    private $productModel;
    
    public function __construct() {
        parent::__construct();
        $this->kirimModel = new Kirim();
        $this->yetkazibModel = new YetkazibBeruvchi();
        $this->productModel = new Product();
    }
    
    /**
     * Kirimlar ro'yxati
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        // Barcha kirimlarni olish
        $stmt = $this->db->query("
            SELECT k.*, y.nomi as yetkazib_beruvchi_nomi, u.fio as kiritgan_fio
            FROM kirimlar k
            LEFT JOIN yetkazib_beruvchilar y ON k.yetkazib_beruvchi_id = y.id
            LEFT JOIN foydalanuvchilar u ON k.kiritgan_id = u.id
            ORDER BY k.kirim_vaqt DESC
        ");
        $kirimlar = $stmt->fetchAll();
        
        $this->view('kirim/index', ['kirimlar' => $kirimlar]);
    }
    
    /**
     * Yangi kirim qo'shish formasi
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $yetkazibBeruvchilar = $this->yetkazibModel->all();
        $mahsulotlar = $this->productModel->all();
        
        $this->view('kirim/create', [
            'yetkazibBeruvchilar' => $yetkazibBeruvchilar,
            'mahsulotlar' => $mahsulotlar
        ]);
    }
    
    /**
     * Kirimni saqlash
     */
    public function store() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('kirim/create');
        }

        $yetkazibBeruvchiId = $_POST['yetkazib_beruvchi_id'] ?? 0;
        $izoh = $_POST['izoh'] ?? '';
        $mahsulotlar = $_POST['mahsulot_id'] ?? [];
        $sonlar = $_POST['soni'] ?? [];
        $narxlar = $_POST['kelish_narxi'] ?? [];

        if (empty($yetkazibBeruvchiId) || empty($mahsulotlar)) {
            $_SESSION['flash']['error'] = 'Yetkazib beruvchi va mahsulotlar tanlanishi shart';
            $this->redirect('kirim/create');
        }

        $this->db->beginTransaction();

        try {
            // 1. Kirim yozuvini yaratish
            $stmt = $this->db->prepare("
                INSERT INTO kirimlar (yetkazib_beruvchi_id, kiritgan_id, umumiy_summa, holat, izoh, kirim_vaqt)
                VALUES (?, ?, ?, 'QABUL_QILINDI', ?, NOW())
            ");
            $stmt->execute([$yetkazibBeruvchiId, $_SESSION['user_id'], 0, $izoh]);
            $kirimId = $this->db->lastInsertId();

            $totalSumma = 0;

            // 2. Har bir mahsulotni kirim tarkibiga qo'shish va omborni yangilash
            for ($i = 0; $i < count($mahsulotlar); $i++) {
                $mahsulotId = $mahsulotlar[$i];
                $soni = floatval(str_replace(',', '.', $sonlar[$i] ?? 0));
                $soni = round($soni, 3);
                $kelishNarxi = floatval(str_replace(',', '.', $narxlar[$i] ?? 0));

                if ($soni <= 0 || $kelishNarxi <= 0) continue;

                $qatorSumma = $soni * $kelishNarxi;
                $totalSumma += $qatorSumma;

                // Kirim tarkibi
                $stmt = $this->db->prepare("
                    INSERT INTO kirim_tarkibi (kirim_id, mahsulot_id, soni, birlik_kelish_narxi, qator_summa)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$kirimId, $mahsulotId, $soni, $kelishNarxi, $qatorSumma]);

                // Mahsulot miqdorini oshirish
                $stmt = $this->db->prepare("UPDATE mahsulotlar SET miqdor = miqdor + ? WHERE id = ?");
                $stmt->execute([$soni, $mahsulotId]);
            }

            // 3. Kirim umumiy summasini yangilash
            $stmt = $this->db->prepare("UPDATE kirimlar SET umumiy_summa = ? WHERE id = ?");
            $stmt->execute([$totalSumma, $kirimId]);

            // 4. Diller ma'lumotlarini yangilash
            $stmt = $this->db->prepare("
                UPDATE yetkazib_beruvchilar
                SET 
                    jami_olingan = jami_olingan + ?,
                    oxirgi_olingan_sana = CURDATE(),
                    qarz = qarz + ?
                WHERE id = ?
            ");
            $stmt->execute([$totalSumma, $totalSumma, $yetkazibBeruvchiId]);

            $this->db->commit();

            $_SESSION['flash']['success'] = 'Kirim muvaffaqiyatli qoshildi';
            $this->redirect('kirim/show/' . $kirimId);

        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Kirim store error: " . $e->getMessage());
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
            $this->redirect('kirim/create');
        }
    }
    
    /**
     * Kirim detallarini ko'rish (show metodi - view emas!)
     */
    public function show($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        // Kirim ma'lumotlarini olish
        $stmt = $this->db->prepare("
            SELECT k.*, y.nomi as yetkazib_beruvchi_nomi, u.fio as kiritgan_fio
            FROM kirimlar k
            LEFT JOIN yetkazib_beruvchilar y ON k.yetkazib_beruvchi_id = y.id
            LEFT JOIN foydalanuvchilar u ON k.kiritgan_id = u.id
            WHERE k.id = ?
        ");
        $stmt->execute([$id]);
        $kirim = $stmt->fetch();
        
        if (!$kirim) {
            $_SESSION['flash']['error'] = 'Kirim topilmadi';
            $this->redirect('kirim');
        }
        
        // Kirim tarkibidagi mahsulotlar
        $stmt = $this->db->prepare("
            SELECT kt.*, m.nomi, m.shtrix_kod, m.birlik
            FROM kirim_tarkibi kt
            LEFT JOIN mahsulotlar m ON kt.mahsulot_id = m.id
            WHERE kt.kirim_id = ?
            ORDER BY kt.id ASC
        ");
        $stmt->execute([$id]);
        $mahsulotlar = $stmt->fetchAll();
        
        $this->view('kirim/view', [
            'kirim' => $kirim,
            'mahsulotlar' => $mahsulotlar
        ]);
    }
    
    /**
     * Kirimni tahrirlash formasi (agar kerak bo'lsa)
     */
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        // Kirim ma'lumotlarini olish
        $stmt = $this->db->prepare("SELECT * FROM kirimlar WHERE id = ?");
        $stmt->execute([$id]);
        $kirim = $stmt->fetch();
        
        if (!$kirim) {
            $_SESSION['flash']['error'] = 'Kirim topilmadi';
            $this->redirect('kirim');
        }
        
        // Yetkazib beruvchilar va mahsulotlar
        $yetkazibBeruvchilar = $this->yetkazibModel->all();
        $mahsulotlar = $this->productModel->all();
        
        // Kirim tarkibidagi mahsulotlar
        $stmt = $this->db->prepare("SELECT * FROM kirim_tarkibi WHERE kirim_id = ?");
        $stmt->execute([$id]);
        $tarkib = $stmt->fetchAll();
        
        $this->view('kirim/edit', [
            'kirim' => $kirim,
            'tarkib' => $tarkib,
            'yetkazibBeruvchilar' => $yetkazibBeruvchilar,
            'mahsulotlar' => $mahsulotlar
        ]);
    }
    
    /**
     * Kirimni yangilash
     */
    public function update($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('kirim');
        }
        
        // Bu metodni murakkabligi sababli hozircha qoldiramiz
        // Sizga kerak bo'lsa, alohida so'rov yozishingiz mumkin
        
        $_SESSION['flash']['error'] = 'Kirim tahrirlash hozircha mavjud emas';
        $this->redirect('kirim/show/' . $id);
    }
    
    /**
     * Kirimni o'chirish (soft delete)
     */
    public function delete($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('kirim');
        }
        
        try {
            $this->db->beginTransaction();
            
            // Kirim tarkibidagi mahsulotlar miqdorini kamaytirish
            $stmt = $this->db->prepare("
                SELECT mahsulot_id, soni FROM kirim_tarkibi WHERE kirim_id = ?
            ");
            $stmt->execute([$id]);
            $tarkib = $stmt->fetchAll();
            
            foreach ($tarkib as $item) {
                $soni = floatval($item['soni']);
                $stmt = $this->db->prepare("UPDATE mahsulotlar SET miqdor = miqdor - ? WHERE id = ?");
                $stmt->execute([$soni, $item['mahsulot_id']]);
            }
            
            // Diller qarzini kamaytirish (agar kerak bo'lsa)
            $stmt = $this->db->prepare("SELECT yetkazib_beruvchi_id, umumiy_summa FROM kirimlar WHERE id = ?");
            $stmt->execute([$id]);
            $kirim = $stmt->fetch();
            
            if ($kirim) {
                $stmt = $this->db->prepare("
                    UPDATE yetkazib_beruvchilar
                    SET jami_olingan = jami_olingan - ?, qarz = qarz - ?
                    WHERE id = ?
                ");
                $stmt->execute([$kirim['umumiy_summa'], $kirim['umumiy_summa'], $kirim['yetkazib_beruvchi_id']]);
            }
            
            // Kirimni o'chirish (soft delete)
            $stmt = $this->db->prepare("UPDATE kirimlar SET ochirilgan_vaqt = NOW() WHERE id = ?");
            $stmt->execute([$id]);
            
            $this->db->commit();
            
            $_SESSION['flash']['success'] = 'Kirim o\'chirildi';
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Kirim delete error: " . $e->getMessage());
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
        }
        
        $this->redirect('kirim');
    }
}