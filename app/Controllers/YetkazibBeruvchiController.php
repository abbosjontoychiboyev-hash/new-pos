<?php
namespace App\Controllers;

use App\Models\YetkazibBeruvchi;

class YetkazibBeruvchiController extends Controller {
    private $yetkazibModel;

    public function __construct() {
        parent::__construct();
        $this->yetkazibModel = new YetkazibBeruvchi();
    }

    /**
     * Barcha dillerlar roʻyxati
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $dillerlar = $this->yetkazibModel->all();
        $qarzdorlar = $this->yetkazibModel->getDebtors();

        // Har bir diller uchun oxirgi kirim ma'lumotini olish
        foreach ($dillerlar as &$d) {
            $d['last_kirim'] = $this->yetkazibModel->getLastKirim($d['id']);
        }

        $this->view('yetkazib/index', [
            'dillerlar' => $dillerlar,
            'qarzdorlar' => $qarzdorlar
        ]);
    }

    /**
     * Diller ma'lumotlarini koʻrish (barcha kirimlar, toʻlovlar)
     */
    public function show($id) {  // view -> show
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $diller = $this->yetkazibModel->find($id);
        if (!$diller) {
            $_SESSION['flash']['error'] = 'Diller topilmadi';
            $this->redirect('yetkazib');
        }

        $kirimlar = $this->yetkazibModel->getKirimlar($id, 50);
        foreach ($kirimlar as &$k) {
            $k['mahsulotlar'] = $this->yetkazibModel->getKirimProducts($k['id']);
        }

        $tolovlar = $this->yetkazibModel->getPayments($id, 50);

        $this->view('yetkazib/show', [
            'diller' => $diller,
            'kirimlar' => $kirimlar,
            'tolovlar' => $tolovlar
        ]);
    }

    /**
     * Toʻlov qoʻshish formasi
     */
    public function addPayment($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $diller = $this->yetkazibModel->find($id);
        if (!$diller) {
            $_SESSION['flash']['error'] = 'Diller topilmadi';
            $this->redirect('yetkazib');
        }

        $this->view('yetkazib/payment', ['diller' => $diller]);
    }

    /**
     * Toʻlovni saqlash
     */
    public function storePayment() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('yetkazib');
        }

        $rules = [
            'yetkazib_beruvchi_id' => 'required|numeric',
            'summa' => 'required|numeric|min:1',
            'usul' => 'required'
        ];

        if (!$this->validate($_POST, $rules)) {
            $this->redirect('yetkazib/add-payment/' . $_POST['yetkazib_beruvchi_id']);
        }

        $supplierId = $_POST['yetkazib_beruvchi_id'];
        $summa = floatval(str_replace(',', '', $_POST['summa']));
        $usul = $_POST['usul'];
        $izoh = $_POST['izoh'] ?? '';

        // Diller qarzini tekshirish
        $diller = $this->yetkazibModel->find($supplierId);
        if (!$diller) {
            $_SESSION['flash']['error'] = 'Diller topilmadi';
            $this->redirect('yetkazib');
        }

        if ($summa > $diller['qarz']) {
            $_SESSION['flash']['error'] = 'Toʻlov summasi qarzdan oshib ketdi. Qolgan qarz: ' . number_format($diller['qarz'], 0, ',', ' ') . ' soʻm';
            $this->redirect('yetkazib/add-payment/' . $supplierId);
        }

        $data = [
            'yetkazib_beruvchi_id' => $supplierId,
            'summa' => $summa,
            'usul' => $usul,
            'izoh' => $izoh
        ];

        try {
            $this->yetkazibModel->addPayment($data, $_SESSION['user_id']);
            $_SESSION['flash']['success'] = 'Toʻlov muvaffaqiyatli amalga oshirildi';
            $this->redirect('yetkazib/show/' . $supplierId);
        } catch (\Exception $e) {
            error_log("Store payment error: " . $e->getMessage());
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi: ' . $e->getMessage();
            $this->redirect('yetkazib/add-payment/' . $supplierId);
        }
    }

    /**
     * Yangi diller qoʻshish formasi
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        $this->view('yetkazib/create');
    }

    /**
     * Diller ma'lumotlarini saqlash
     */
    public function store() {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('yetkazib/create');
        }

        $rules = [
            'nomi' => 'required|min:2|max:180'
        ];

        if (!$this->validate($_POST, $rules)) {
            $this->redirect('yetkazib/create');
        }

        $data = [
            'nomi' => $_POST['nomi'],
            'telefon' => $_POST['telefon'] ?? null,
            'manzil' => $_POST['manzil'] ?? null,
            'izoh' => $_POST['izoh'] ?? null,
            'kelish_kuni' => $_POST['kelish_kuni'] ?? null,
            'tolash_muddati' => $_POST['tolash_muddati'] ?? null,
            'tolash_eslatma' => $_POST['tolash_eslatma'] ?? null
        ];

        $id = $this->yetkazibModel->create($data);
        $_SESSION['flash']['success'] = 'Diller qoʻshildi';
        $this->redirect('yetkazib/show/' . $id);
    }

    /**
     * Diller ma'lumotlarini tahrirlash formasi
     */
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $diller = $this->yetkazibModel->find($id);
        if (!$diller) {
            $_SESSION['flash']['error'] = 'Diller topilmadi';
            $this->redirect('yetkazib');
        }

        $this->view('yetkazib/edit', ['diller' => $diller]);
    }

    /**
     * Diller ma'lumotlarini yangilash
     */
    public function update($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('yetkazib/edit/' . $id);
        }

        $rules = [
            'nomi' => 'required|min:2|max:180'
        ];

        if (!$this->validate($_POST, $rules)) {
            $this->redirect('yetkazib/edit/' . $id);
        }

        $data = [
            'nomi' => $_POST['nomi'],
            'telefon' => $_POST['telefon'] ?? null,
            'manzil' => $_POST['manzil'] ?? null,
            'izoh' => $_POST['izoh'] ?? null,
            'kelish_kuni' => $_POST['kelish_kuni'] ?? null,
            'tolash_muddati' => $_POST['tolash_muddati'] ?? null,
            'tolash_eslatma' => $_POST['tolash_eslatma'] ?? null
        ];

        $this->yetkazibModel->update($id, $data);
        $_SESSION['flash']['success'] = 'Diller yangilandi';
        $this->redirect('yetkazib/show/' . $id);
    }

    /**
     * Diller oʻchirish (soft delete)
     */
    public function delete($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('yetkazib');
        }

        if ($this->yetkazibModel->delete($id)) {
            $_SESSION['flash']['success'] = 'Diller oʻchirildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        $this->redirect('yetkazib');
    }
}