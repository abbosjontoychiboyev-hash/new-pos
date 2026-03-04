<?php
// app/Models/Subcategory.php

namespace App\Models;

class Subcategory extends Model {
    protected $table = 'subkategoriyalar';
    protected $primaryKey = 'id';
    protected $fillable = ['kategoriya_id', 'nomi', 'izoh', 'faol', 'tartib'];
    
    /**
     * Kategoriya ma'lumotini olish
     */
    public function category() {
        $categoryModel = new Category();
        return $categoryModel->find($this->kategoriya_id);
    }
    
    /**
     * Subkategoriyadagi mahsulotlar soni
     */
    public function productsCount($subcategoryId = null) {
        $id = $subcategoryId ?: $this->id;
        
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM mahsulotlar 
            WHERE subkategoriya_id = ? AND ochirilgan_vaqt IS NULL
        ");
        $stmt->execute([$id]);
        return $stmt->fetch()['count'];
    }
    
    /**
     * Kategoriya bo'yicha subkategoriyalar
     */
    public function getByCategory($categoryId, $activeOnly = true) {
        $sql = "SELECT * FROM {$this->table} WHERE kategoriya_id = ? AND ochirilgan_vaqt IS NULL";
        
        if ($activeOnly) {
            $sql .= " AND faol = 1";
        }
        
        $sql .= " ORDER BY tartib ASC, nomi ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Kategoriya nomi bilan birga olish
     */
    public function getAllWithCategory() {
        $stmt = $this->db->prepare("
            SELECT 
                s.*,
                k.nomi as kategoriya_nomi,
                (SELECT COUNT(*) FROM mahsulotlar WHERE subkategoriya_id = s.id AND ochirilgan_vaqt IS NULL) as products_count
            FROM {$this->table} s
            LEFT JOIN kategoriyalar k ON s.kategoriya_id = k.id
            WHERE s.ochirilgan_vaqt IS NULL
            ORDER BY k.nomi ASC, s.tartib ASC, s.nomi ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}