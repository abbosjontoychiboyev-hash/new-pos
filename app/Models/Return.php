<?php
// app/Models/Returns.php

namespace App\Models;

class Returns extends Model {
    
    public function test() {
        return "Model ishlayapti";
    }
    
    public function getSaleById($saleId) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.fio as kassir_fio, m.fio as mijoz_fio
            FROM savdolar s
            LEFT JOIN foydalanuvchilar u ON s.kassir_id = u.id
            LEFT JOIN mijozlar m ON s.mijoz_id = m.id
            WHERE s.id = ? AND s.holat = 'YAKUNLANGAN'
        ");
        $stmt->execute([$saleId]);
        return $stmt->fetch();
    }
    
    public function findSaleByReceipt($receiptNumber) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.fio as kassir_fio, m.fio as mijoz_fio
            FROM savdolar s
            LEFT JOIN foydalanuvchilar u ON s.kassir_id = u.id
            LEFT JOIN mijozlar m ON s.mijoz_id = m.id
            WHERE s.chek_raqami = ? AND s.holat = 'YAKUNLANGAN'
        ");
        $stmt->execute([$receiptNumber]);
        return $stmt->fetch();
    }
    
    public function getSaleItems($saleId) {
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
        $stmt->execute([$saleId]);
        return $stmt->fetchAll();
    }
    
    public function returnProduct($saleId, $itemId, $quantity, $reason, $userId) {
        // Oddiy versiya
        return ['success' => true, 'return_amount' => 10000, 'quantity' => $quantity];
    }
    
    public function returnFullSale($saleId, $reason, $userId) {
        // Oddiy versiya
        return ['success' => true, 'return_amount' => 50000, 'items_count' => 2];
    }
}