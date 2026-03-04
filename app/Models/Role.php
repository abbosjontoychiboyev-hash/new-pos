<?php
// app/Models/Role.php

namespace App\Models;

class Role extends Model {
    protected $table = 'rollar';
    protected $primaryKey = 'id';
    protected $fillable = ['nomi', 'izoh'];
    
    /**
     * Rol nomi bo'yicha topish
     */
    public function findByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE nomi = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }
    
    /**
     * Rolga tegishli foydalanuvchilar soni
     */
    public function getUserCount($roleId = null) {
        $id = $roleId ?: $this->id;
        
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM foydalanuvchilar 
            WHERE rol_id = ? AND ochirilgan_vaqt IS NULL
        ");
        $stmt->execute([$id]);
        return $stmt->fetch()['count'];
    }
    
    /**
     * Barcha rollarni foydalanuvchilar soni bilan
     */
    public function getAllWithCount() {
        $stmt = $this->db->query("
            SELECT 
                r.*,
                (SELECT COUNT(*) FROM foydalanuvchilar WHERE rol_id = r.id AND ochirilgan_vaqt IS NULL) as users_count
            FROM {$this->table} r
            ORDER BY r.id ASC
        ");
        return $stmt->fetchAll();
    }
}