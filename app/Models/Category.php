<?php
// app/Models/Category.php

namespace App\Models;

class Category extends Model {
    protected $table = 'kategoriyalar';
    protected $primaryKey = 'id';
    protected $fillable = ['nomi', 'izoh', 'faol', 'tartib'];
    
    /**
     * Subkategoriyalarni olish
     */
    public function subcategories() {
        $subcategoryModel = new Subcategory();
        return $subcategoryModel->where(['kategoriya_id' => $this->id, 'faol' => 1], 'tartib ASC');
    }
    
    /**
     * Kategoriyadagi mahsulotlar soni
     */
    public function productsCount($categoryId = null) {
        $id = $categoryId ?: $this->id;
        
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM mahsulotlar 
            WHERE kategoriya_id = ? AND ochirilgan_vaqt IS NULL
        ");
        $stmt->execute([$id]);
        return $stmt->fetch()['count'];
    }
    
    /**
     * Faol kategoriyalar
     */
    public function active() {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE ochirilgan_vaqt IS NULL AND faol = 1 
            ORDER BY tartib ASC, nomi ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Qidirish
     */
    public function search($keyword) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE ochirilgan_vaqt IS NULL 
            AND nomi LIKE ? 
            ORDER BY tartib ASC, nomi ASC
        ");
        $stmt->execute(["%{$keyword}%"]);
        return $stmt->fetchAll();
    }
    
    /**
     * Kategoriya va subkategoriyalar bilan birga olish
     */
    public function getWithSubcategories() {
        $stmt = $this->db->prepare("
            SELECT 
                k.*,
                (SELECT COUNT(*) FROM subkategoriyalar WHERE kategoriya_id = k.id AND ochirilgan_vaqt IS NULL) as subcategories_count,
                (SELECT COUNT(*) FROM mahsulotlar WHERE kategoriya_id = k.id AND ochirilgan_vaqt IS NULL) as products_count
            FROM {$this->table} k
            WHERE k.ochirilgan_vaqt IS NULL
            ORDER BY k.tartib ASC, k.nomi ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}