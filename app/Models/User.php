<?php
// app/Models/User.php

namespace App\Models;

class User extends Model {
    protected $table = 'foydalanuvchilar';
    protected $primaryKey = 'id';
    protected $fillable = [
        'rol_id', 'fio', 'email', 'telefon', 
        'login', 'parol_hash', 'faol'
    ];
    protected $hidden = ['parol_hash'];
    
    /**
     * Foydalanuvchini login bo'yicha topish
     */
    public function findByLogin($login) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nomi as rol_nomi 
            FROM {$this->table} u
            LEFT JOIN rollar r ON u.rol_id = r.id
            WHERE u.login = ? AND u.ochirilgan_vaqt IS NULL
        ");
        $stmt->execute([$login]);
        return $stmt->fetch();
    }
    
    /**
     * Rol nomi bilan foydalanuvchilarni olish
     */
    public function getByRole($roleName) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nomi as rol_nomi 
            FROM {$this->table} u
            JOIN rollar r ON u.rol_id = r.id
            WHERE r.nomi = ? AND u.ochirilgan_vaqt IS NULL
            ORDER BY u.fio ASC
        ");
        $stmt->execute([$roleName]);
        return $stmt->fetchAll();
    }
    /**
     * Barcha foydalanuvchilarni olish (soft delete hisobga olingan holda)
     */
    public function all($orderBy = 'fio', $direction = 'ASC') {
        // Soft delete ustuni borligini tekshirish
        try {
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'ochirilgan_vaqt'");
            $stmt->execute();
            $hasSoftDelete = $stmt->fetch() ? true : false;
            
            if ($hasSoftDelete) {
                $sql = "SELECT u.*, r.nomi as rol_nomi 
                        FROM {$this->table} u
                        LEFT JOIN rollar r ON u.rol_id = r.id
                        WHERE u.ochirilgan_vaqt IS NULL
                        ORDER BY u.{$orderBy} {$direction}";
            } else {
                $sql = "SELECT u.*, r.nomi as rol_nomi 
                        FROM {$this->table} u
                        LEFT JOIN rollar r ON u.rol_id = r.id
                        ORDER BY u.{$orderBy} {$direction}";
            }
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
            
        } catch (\PDOException $e) {
            // Xatolik bo'lsa, oddiy so'rov
            $sql = "SELECT u.*, r.nomi as rol_nomi 
                    FROM {$this->table} u
                    LEFT JOIN rollar r ON u.rol_id = r.id
                    ORDER BY u.{$orderBy} {$direction}";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        }
    }
    /**
     * Faol foydalanuvchilar
     */
    public function getActive() {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nomi as rol_nomi 
            FROM {$this->table} u
            JOIN rollar r ON u.rol_id = r.id
            WHERE u.faol = 1 AND u.ochirilgan_vaqt IS NULL
            ORDER BY u.fio ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Parolni tekshirish
     */
    public function verifyPassword($userId, $password) {
        $stmt = $this->db->prepare("SELECT parol_hash FROM {$this->table} WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        return $user && password_verify($password, $user['parol_hash']);
    }
    
    /**
     * Parolni yangilash
     */
    public function updatePassword($userId, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("UPDATE {$this->table} SET parol_hash = ? WHERE id = ?");
        return $stmt->execute([$hash, $userId]);
    }
    
    /**
     * Oxirgi kirish vaqtini yangilash
     */
    public function updateLastLogin($userId) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET oxirgi_kirish_vaqt = NOW() WHERE id = ?");
        return $stmt->execute([$userId]);
    }
    
    /**
     * Foydalanuvchini faollashtirish/faolsizlantirish
     */
    public function toggleActive($userId) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET faol = NOT faol WHERE id = ?");
        return $stmt->execute([$userId]);
    }
}