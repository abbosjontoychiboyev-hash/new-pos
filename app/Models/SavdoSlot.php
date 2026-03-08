<?php
namespace App\Models;

class SavdoSlot extends Model {
    protected $table = 'savdo_slotlari';
    protected $primaryKey = 'id';
    protected $fillable = ['kassir_id', 'slot_raqami', 'mijoz_id', 'nom', 'umumiy_summa', 'holat'];
    
    /**
     * Kassirning aktiv slotlarini olish
     */
    public function getActiveSlots($kassirId) {
        $stmt = $this->db->prepare("
            SELECT s.*, m.fio as mijoz_fio
            FROM {$this->table} s
            LEFT JOIN mijozlar m ON s.mijoz_id = m.id
            WHERE s.kassir_id = ? AND s.holat IN ('aktiv', 'kutilmoqda')
            ORDER BY s.slot_raqami ASC
        ");
        $stmt->execute([$kassirId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Slotdagi mahsulotlarni olish
     */
    public function getSlotItems($slotId) {
        $stmt = $this->db->prepare("
            SELECT i.*, p.nomi, p.shtrix_kod, p.birlik, p.miqdor as ombor_qoldiq
            FROM savdo_slot_items i
            JOIN mahsulotlar p ON i.mahsulot_id = p.id
            WHERE i.slot_id = ?
            ORDER BY i.id ASC
        ");
        $stmt->execute([$slotId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Yangi slot yaratish
     */
    public function createSlot($kassirId) {
        // Eng katta slot raqamini topish
        $stmt = $this->db->prepare("
            SELECT MAX(slot_raqami) as max_slot 
            FROM {$this->table} 
            WHERE kassir_id = ? AND holat IN ('aktiv', 'kutilmoqda')
        ");
        $stmt->execute([$kassirId]);
        $maxSlot = $stmt->fetch()['max_slot'] ?? 0;
        $newSlotNum = $maxSlot + 1;
        
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (kassir_id, slot_raqami, nom, holat)
            VALUES (?, ?, ?, 'aktiv')
        ");
        $stmt->execute([$kassirId, $newSlotNum, "Mijoz {$newSlotNum}"]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Slotga mahsulot qo'shish
     */
    public function addProduct($slotId, $mahsulotId, $soni = 1) {
        // Mahsulot ma'lumotlarini olish
        $stmt = $this->db->prepare("SELECT sotish_narxi FROM mahsulotlar WHERE id = ?");
        $stmt->execute([$mahsulotId]);
        $mahsulot = $stmt->fetch();
        
        if (!$mahsulot) {
            return false;
        }
        
        $narx = $mahsulot['sotish_narxi'];
        $qatorSumma = $narx * $soni;
        
        // Mahsulot slotda borligini tekshirish
        $stmt = $this->db->prepare("
            SELECT id, soni FROM savdo_slot_items 
            WHERE slot_id = ? AND mahsulot_id = ?
        ");
        $stmt->execute([$slotId, $mahsulotId]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Mavjud mahsulot sonini oshirish
            $yangiSoni = $existing['soni'] + $soni;
            $yangiSumma = $narx * $yangiSoni;
            
            $stmt = $this->db->prepare("
                UPDATE savdo_slot_items 
                SET soni = ?, qator_summa = ? 
                WHERE id = ?
            ");
            $stmt->execute([$yangiSoni, $yangiSumma, $existing['id']]);
        } else {
            // Yangi qator qo'shish
            $stmt = $this->db->prepare("
                INSERT INTO savdo_slot_items (slot_id, mahsulot_id, soni, birlik_narx, qator_summa)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$slotId, $mahsulotId, $soni, $narx, $qatorSumma]);
        }
        
        // Slot umumiy summasini yangilash
        $this->updateSlotTotal($slotId);
        
        return true;
    }
    
    /**
     * Slotdan mahsulot o'chirish
     */
    public function removeProduct($slotId, $mahsulotId) {
        $stmt = $this->db->prepare("
            DELETE FROM savdo_slot_items 
            WHERE slot_id = ? AND mahsulot_id = ?
        ");
        $result = $stmt->execute([$slotId, $mahsulotId]);
        
        if ($result) {
            $this->updateSlotTotal($slotId);
        }
        
        return $result;
    }
    
    /**
     * Slotdagi mahsulot sonini yangilash
     */
    public function updateQuantity($slotId, $mahsulotId, $yangiSoni) {
        $stmt = $this->db->prepare("
            SELECT birlik_narx FROM savdo_slot_items 
            WHERE slot_id = ? AND mahsulot_id = ?
        ");
        $stmt->execute([$slotId, $mahsulotId]);
        $item = $stmt->fetch();
        
        if (!$item) {
            return false;
        }
        
        $yangiSumma = $item['birlik_narx'] * $yangiSoni;
        
        $stmt = $this->db->prepare("
            UPDATE savdo_slot_items 
            SET soni = ?, qator_summa = ? 
            WHERE slot_id = ? AND mahsulot_id = ?
        ");
        $result = $stmt->execute([$yangiSoni, $yangiSumma, $slotId, $mahsulotId]);
        
        if ($result) {
            $this->updateSlotTotal($slotId);
        }
        
        return $result;
    }
    
    /**
     * Slot umumiy summasini yangilash
     */
    public function updateSlotTotal($slotId) {
        $stmt = $this->db->prepare("
            SELECT SUM(qator_summa) as jami FROM savdo_slot_items WHERE slot_id = ?
        ");
        $stmt->execute([$slotId]);
        $jami = $stmt->fetch()['jami'] ?? 0;
        
        $stmt = $this->db->prepare("
            UPDATE {$this->table} SET umumiy_summa = ? WHERE id = ?
        ");
        return $stmt->execute([$jami, $slotId]);
    }
    
    /**
     * Slot holatini o'zgartirish
     */
    public function updateStatus($slotId, $holat) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} SET holat = ? WHERE id = ?
        ");
        return $stmt->execute([$holat, $slotId]);
    }
    
    /**
     * Slot ma'lumotlarini yangilash
     */
    public function updateSlot($slotId, $data) {
        $fields = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $fields[] = "{$key} = ?";
                $params[] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $slotId;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Slotni tugatish (savdoga aylantirish)
     */
    public function completeSlot($slotId, $tolovMalumotlari) {
        $this->db->beginTransaction();
        
        try {
            // Slot ma'lumotlarini olish
            $stmt = $this->db->prepare("
                SELECT s.*, i.* 
                FROM {$this->table} s
                LEFT JOIN savdo_slot_items i ON s.id = i.slot_id
                WHERE s.id = ?
            ");
            $stmt->execute([$slotId]);
            $slot = $stmt->fetch();
            
            if (!$slot) {
                throw new \Exception("Slot topilmadi");
            }
            
            // Slotdagi mahsulotlarni olish
            $items = $this->getSlotItems($slotId);
            
            if (empty($items)) {
                throw new \Exception("Slot bo'sh");
            }
            
            // Savdo yaratish
            $saleModel = new Pos();
            $saleData = [
                'chek_raqami' => $saleModel->generateChekRaqami(),
                'mijoz_id' => $slot['mijoz_id'],
                'tolov_usuli' => $tolovMalumotlari['usul'],
                'tolov_holati' => $tolovMalumotlari['holat'],
                'umumiy_summa' => $slot['umumiy_summa'],
                'chegirma_summa' => $tolovMalumotlari['chegirma'] ?? 0,
                'yakuniy_summa' => $slot['umumiy_summa'] - ($tolovMalumotlari['chegirma'] ?? 0),
                'tolangan_summa' => $tolovMalumotlari['tolangan'] ?? $slot['umumiy_summa'],
                'qarz_summa' => $tolovMalumotlari['qarz'] ?? 0,
                'holat' => 'YAKUNLANGAN',
                'izoh' => $tolovMalumotlari['izoh'] ?? "Slot #{$slot['slot_raqami']} dan yakunlandi"
            ];
            
            // Savdo tarkibi
            $saleItems = [];
            foreach ($items as $item) {
                $saleItems[] = [
                    'mahsulot_id' => $item['mahsulot_id'],
                    'soni' => $item['soni'],
                    'birlik_narx' => $item['birlik_narx'],
                    'chegirma' => $item['chegirma'],
                    'qator_summa' => $item['qator_summa']
                ];
            }
            
            $saleId = $saleModel->saveSale($saleData, $saleItems, $_SESSION['user_id']);
            
            // Slotni tugatilgan deb belgilash
            $this->updateStatus($slotId, 'tugatilgan');
            
            $this->db->commit();
            return $saleId;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Slot complete error: " . $e->getMessage());
            throw $e;
        }
    }
}