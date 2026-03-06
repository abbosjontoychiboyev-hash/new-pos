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
     * Yetkazib beruvchilar ro'yxati
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $stmt = $this->db->prepare("
                SELECT * FROM yetkazib_beruvchilar 
                WHERE nomi LIKE ? AND faol = 1
                ORDER BY nomi ASC
            ");
            $stmt->execute(["%{$search}%"]);
            $yetkazib = $stmt->fetchAll();
            $pagination = ['page' => 1, 'lastPage' => 1, 'total' => count($yetkazib)];
        } else {
            $pagination = $this->yetkazibModel->paginate($page, 20, ['faol' => 1]);
            $yetkazib = $pagination['data'];
        }

        // Bugungi keladiganlar
        $todays = $this->yetkazibModel->getTodaysDeliveries();

        $this->view('yetkazib/index', [
            'yetkazib' => $yetkazib,
            'todays' => $todays,
            'search' => $search,
            'pagination' => $pagination
        ]);
    }

    /**
     * Yangi yetkazib beruvchi qo'shish formasi
     */
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        $this->view('yetkazib/create');
    }

    /**
     * Yangi yetkazib beruvchini saqlash
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
            'kelish_kuni' => $_POST['kelish_kuni'] ?? null,
            'izoh' => $_POST['izoh'] ?? null,
            'faol' => isset($_POST['faol']) ? 1 : 1
        ];

        try {
            $this->yetkazibModel->create($data);
            $_SESSION['flash']['success'] = 'Yetkazib beruvchi qo\'shildi';
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik: ' . $e->getMessage();
        }

        $this->redirect('yetkazib');
    }

    /**
     * Tahrirlash formasi
     */
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        $yetkazib = $this->yetkazibModel->find($id);
        if (!$yetkazib) {
            $_SESSION['flash']['error'] = 'Yetkazib beruvchi topilmadi';
            $this->redirect('yetkazib');
        }
        $this->view('yetkazib/edit', ['yetkazib' => $yetkazib]);
    }

    /**
     * Yangilash
     */
    public function update($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect("yetkazib/edit/$id");
        }

        $rules = ['nomi' => 'required|min:2|max:180'];
        if (!$this->validate($_POST, $rules)) {
            $this->redirect("yetkazib/edit/$id");
        }

        $data = [
            'nomi' => $_POST['nomi'],
            'telefon' => $_POST['telefon'] ?? null,
            'manzil' => $_POST['manzil'] ?? null,
            'kelish_kuni' => $_POST['kelish_kuni'] ?? null,
            'izoh' => $_POST['izoh'] ?? null,
            'faol' => isset($_POST['faol']) ? 1 : 0
        ];

        if ($this->yetkazibModel->update($id, $data)) {
            $_SESSION['flash']['success'] = 'Yetkazib beruvchi yangilandi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        $this->redirect('yetkazib');
    }

    public function delete($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('yetkazib');
        }
        
        // Bog'liq kirimlar borligini tekshirish
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM kirimlar WHERE yetkazib_beruvchi_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetch()['count'];
        
        if ($count > 0) {
            $_SESSION['flash']['error'] = 'Bu yetkazib beruvchiga tegishli kirimlar mavjud. Avval ularni o\'chiring yoki boshqa yetkazib beruvchiga o\'tkazing.';
            $this->redirect('yetkazib');
        }
        
        if ($this->yetkazibBeruvchiModel->delete($id)) {
            $_SESSION['flash']['success'] = 'Yetkazib beruvchi o\'chirildi';
        } else {
            $_SESSION['flash']['error'] = 'Xatolik yuz berdi';
        }
        
        $this->redirect('yetkazib');
    }

    /**
     * Qarzni to'lash
     */
    public function payDebt($id) {
        if (!isset($_POST['csrf_token']) || !validate_csrf($_POST['csrf_token'])) {
            $_SESSION['flash']['error'] = 'CSRF token xato';
            $this->redirect('yetkazib');
        }

        $summa = floatval($_POST['summa'] ?? 0);
        if ($summa <= 0) {
            $_SESSION['flash']['error'] = 'To\'lov summasi noto\'g\'ri';
            $this->redirect('yetkazib');
        }

        try {
            $this->yetkazibModel->makePayment($id, $summa);
            $_SESSION['flash']['success'] = 'To\'lov amalga oshirildi';
        } catch (\Exception $e) {
            $_SESSION['flash']['error'] = 'Xatolik: ' . $e->getMessage();
        }
        $this->redirect('yetkazib');
    }
}