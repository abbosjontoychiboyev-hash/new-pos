<?php
// app/Models/Setting.php

namespace App\Models;

class Setting extends Model {
    protected $table = 'sozlamalar';
    protected $primaryKey = 'id';
    protected $fillable = ['kalit_soz', 'qiymat'];
    
    /**
     * Sozlamani kalit bo'yicha olish
     */
    public function get($key, $default = null) {
        $stmt = $this->db->prepare("SELECT qiymat FROM {$this->table} WHERE kalit_soz = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        
        return $result ? $result['qiymat'] : $default;
    }
    /**
     * Kompaniya nomini olish
     */
    public function getCompanyName() {
        return $this->get('company_name', 'POS Magazin');
    }
    /**
     * Sozlamani o'rnatish
     */
    public function set($key, $value) {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE kalit_soz = ?");
        $stmt->execute([$key]);
        
        if ($stmt->fetch()) {
            // Yangilash
            $stmt = $this->db->prepare("UPDATE {$this->table} SET qiymat = ? WHERE kalit_soz = ?");
            return $stmt->execute([$value, $key]);
        } else {
            // Qo'shish
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (kalit_soz, qiymat) VALUES (?, ?)");
            return $stmt->execute([$key, $value]);
        }
    }
    
    /**
     * Bir nechta sozlamani olish
     */
    public function getMultiple($keys = []) {
        if (empty($keys)) {
            $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY kalit_soz ASC");
            return $stmt->fetchAll();
        }
        
        $placeholders = implode(',', array_fill(0, count($keys), '?'));
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE kalit_soz IN ({$placeholders})");
        $stmt->execute($keys);
        
        $result = [];
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $result[$row['kalit_soz']] = $row['qiymat'];
        }
        
        return $result;
    }
    
    /**
     * Sozlamani o'chirish
     */
    public function remove($key) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE kalit_soz = ?");
        return $stmt->execute([$key]);
    }
    
    /**
     * Kompaniya ma'lumotlarini olish
     */
    public function getCompanyInfo() {
        return $this->getMultiple([
            'company_name',
            'company_address',
            'company_phone',
            'company_email',
            'company_tax_number',
            'company_logo'
        ]);
    }
    
    /**
     * Valyuta sozlamalarini olish
     */
    public function getCurrencySettings() {
        return $this->getMultiple([
            'currency_name',
            'currency_symbol',
            'currency_position', // 'left' or 'right'
            'decimal_places',
            'thousand_separator'
        ]);
    }
    
    /**
     * Soliq sozlamalarini olish
     */
    public function getTaxSettings() {
        return $this->getMultiple([
            'tax_name',
            'tax_rate',
            'tax_included' // 0 or 1
        ]);
    }
    
    /**
     * POS sozlamalarini olish
     */
    public function getPosSettings() {
        return $this->getMultiple([
            'receipt_header',
            'receipt_footer',
            'auto_print_receipt',
            'show_customer_on_receipt',
            'default_payment_method'
        ]);
    }
    
    /**
     * Bildirishnoma sozlamalari
     */
    public function getNotificationSettings() {
        return $this->getMultiple([
            'low_stock_alert',
            'debt_alert_days',
            'email_notifications',
            'sms_notifications'
        ]);
    }
}