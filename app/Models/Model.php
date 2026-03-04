<?php
// app/Models/Model.php

namespace App\Models;

use PDO;

abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = ['parol_hash'];
    
    public function __construct() {
        $this->db = \Database::getInstance();
    }
    
    /**
     * Barcha yozuvlarni olish - TUZATILGAN
     */
    public function all($orderBy = null, $direction = 'ASC') {
        // Avval jadvalda ochirilgan_vaqt ustuni borligini tekshirish
        try {
            // Soft delete ustuni borligini tekshirish
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'ochirilgan_vaqt'");
            $stmt->execute();
            $hasSoftDelete = $stmt->fetch() ? true : false;
            
            if ($hasSoftDelete) {
                $sql = "SELECT * FROM {$this->table} WHERE ochirilgan_vaqt IS NULL";
            } else {
                $sql = "SELECT * FROM {$this->table}";
            }
            
            if ($orderBy) {
                $sql .= " ORDER BY {$orderBy} {$direction}";
            }
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
            
        } catch (\PDOException $e) {
            // Agar xatolik bo'lsa, oddiy so'rov yuborish
            $sql = "SELECT * FROM {$this->table}";
            if ($orderBy) {
                $sql .= " ORDER BY {$orderBy} {$direction}";
            }
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        }
    }
    
    /**
     * ID bo'yicha topish - TUZATILGAN
     */
    public function find($id) {
        try {
            // Soft delete ustuni borligini tekshirish
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'ochirilgan_vaqt'");
            $stmt->execute();
            $hasSoftDelete = $stmt->fetch() ? true : false;
            
            if ($hasSoftDelete) {
                $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? AND ochirilgan_vaqt IS NULL");
            } else {
                $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
            }
            
            $stmt->execute([$id]);
            return $stmt->fetch();
            
        } catch (\PDOException $e) {
            // Agar xatolik bo'lsa, oddiy so'rov yuborish
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        }
    }
    
    /**
     * Shart bo'yicha topish - TUZATILGAN
     */
    public function where($conditions = [], $orderBy = null, $limit = null) {
        try {
            // Soft delete ustuni borligini tekshirish
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'ochirilgan_vaqt'");
            $stmt->execute();
            $hasSoftDelete = $stmt->fetch() ? true : false;
            
            $sql = "SELECT * FROM {$this->table}";
            
            $whereClauses = [];
            $params = [];
            
            if ($hasSoftDelete) {
                $whereClauses[] = "ochirilgan_vaqt IS NULL";
            }
            
            if (!empty($conditions)) {
                foreach ($conditions as $field => $value) {
                    $whereClauses[] = "{$field} = ?";
                    $params[] = $value;
                }
            }
            
            if (!empty($whereClauses)) {
                $sql .= " WHERE " . implode(' AND ', $whereClauses);
            }
            
            if ($orderBy) {
                $sql .= " ORDER BY {$orderBy}";
            }
            
            if ($limit) {
                $sql .= " LIMIT {$limit}";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
            
        } catch (\PDOException $e) {
            // Agar xatolik bo'lsa, oddiy so'rov yuborish
            $sql = "SELECT * FROM {$this->table}";
            
            $params = [];
            if (!empty($conditions)) {
                $whereClauses = [];
                foreach ($conditions as $field => $value) {
                    $whereClauses[] = "{$field} = ?";
                    $params[] = $value;
                }
                $sql .= " WHERE " . implode(' AND ', $whereClauses);
            }
            
            if ($orderBy) {
                $sql .= " ORDER BY {$orderBy}";
            }
            
            if ($limit) {
                $sql .= " LIMIT {$limit}";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }
    }
    
    /**
     * Yangi yozuv qo'shish
     */
    public function create($data) {
        // Fillable tekshirish
        $data = $this->filterFillable($data);
        
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Yozuvni yangilash
     */
    public function update($id, $data) {
        // Fillable tekshirish
        $data = $this->filterFillable($data);
        
        $setParts = [];
        foreach (array_keys($data) as $field) {
            $setParts[] = "{$field} = ?";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " WHERE {$this->primaryKey} = ?";
        
        $params = array_values($data);
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Yumshoq o'chirish (Soft delete) - agar ustun mavjud bo'lsa
     */
    public function delete($id) {
        try {
            // Soft delete ustuni borligini tekshirish
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'ochirilgan_vaqt'");
            $stmt->execute();
            $hasSoftDelete = $stmt->fetch() ? true : false;
            
            if ($hasSoftDelete) {
                $sql = "UPDATE {$this->table} SET ochirilgan_vaqt = NOW() WHERE {$this->primaryKey} = ?";
            } else {
                $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
            }
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
            
        } catch (\PDOException $e) {
            // Agar xatolik bo'lsa, oddiy o'chirish
            $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        }
    }
    
    /**
     * Qattiq o'chirish (Hard delete)
     */
    public function forceDelete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Faol yozuvlarni olish - TUZATILGAN
     */
    public function active() {
        try {
            // Soft delete ustuni borligini tekshirish
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'ochirilgan_vaqt'");
            $stmt->execute();
            $hasSoftDelete = $stmt->fetch() ? true : false;
            
            // Faol ustuni borligini tekshirish
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'faol'");
            $stmt->execute();
            $hasActive = $stmt->fetch() ? true : false;
            
            $sql = "SELECT * FROM {$this->table} WHERE 1=1";
            
            if ($hasSoftDelete) {
                $sql .= " AND ochirilgan_vaqt IS NULL";
            }
            
            if ($hasActive) {
                $sql .= " AND faol = 1";
            }
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
            
        } catch (\PDOException $e) {
            // Agar xatolik bo'lsa, oddiy so'rov yuborish
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        }
    }
    
    /**
     * Fillable filtr
     */
    protected function filterFillable($data) {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * Pagination - TUZATILGAN
     */
    public function paginate($page = 1, $perPage = 20, $conditions = []) {
        $offset = ($page - 1) * $perPage;
        
        try {
            // Soft delete ustuni borligini tekshirish
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'ochirilgan_vaqt'");
            $stmt->execute();
            $hasSoftDelete = $stmt->fetch() ? true : false;
            
            // Count query
            $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
            
            $countWhereClauses = [];
            $countParams = [];
            
            if ($hasSoftDelete) {
                $countWhereClauses[] = "ochirilgan_vaqt IS NULL";
            }
            
            if (!empty($conditions)) {
                foreach ($conditions as $field => $value) {
                    $countWhereClauses[] = "{$field} = ?";
                    $countParams[] = $value;
                }
            }
            
            if (!empty($countWhereClauses)) {
                $countSql .= " WHERE " . implode(' AND ', $countWhereClauses);
            }
            
            $stmt = $this->db->prepare($countSql);
            $stmt->execute($countParams);
            $total = $stmt->fetch()['total'];
            
            // Data query
            $dataSql = "SELECT * FROM {$this->table}";
            
            $dataWhereClauses = [];
            $dataParams = [];
            
            if ($hasSoftDelete) {
                $dataWhereClauses[] = "ochirilgan_vaqt IS NULL";
            }
            
            if (!empty($conditions)) {
                foreach ($conditions as $field => $value) {
                    $dataWhereClauses[] = "{$field} = ?";
                    $dataParams[] = $value;
                }
            }
            
            if (!empty($dataWhereClauses)) {
                $dataSql .= " WHERE " . implode(' AND ', $dataWhereClauses);
            }
            
            $dataSql .= " LIMIT {$offset}, {$perPage}";
            
            $stmt = $this->db->prepare($dataSql);
            $stmt->execute($dataParams);
            $data = $stmt->fetchAll();
            
            return [
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'lastPage' => ceil($total / $perPage)
            ];
            
        } catch (\PDOException $e) {
            // Agar xatolik bo'lsa, oddiy pagination
            $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
            
            $countParams = [];
            if (!empty($conditions)) {
                $whereClauses = [];
                foreach ($conditions as $field => $value) {
                    $whereClauses[] = "{$field} = ?";
                    $countParams[] = $value;
                }
                $countSql .= " WHERE " . implode(' AND ', $whereClauses);
            }
            
            $stmt = $this->db->prepare($countSql);
            $stmt->execute($countParams);
            $total = $stmt->fetch()['total'];
            
            $dataSql = "SELECT * FROM {$this->table}";
            
            $dataParams = [];
            if (!empty($conditions)) {
                $whereClauses = [];
                foreach ($conditions as $field => $value) {
                    $whereClauses[] = "{$field} = ?";
                    $dataParams[] = $value;
                }
                $dataSql .= " WHERE " . implode(' AND ', $whereClauses);
            }
            
            $dataSql .= " LIMIT {$offset}, {$perPage}";
            
            $stmt = $this->db->prepare($dataSql);
            $stmt->execute($dataParams);
            $data = $stmt->fetchAll();
            
            return [
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'lastPage' => ceil($total / $perPage)
            ];
        }
    }
}