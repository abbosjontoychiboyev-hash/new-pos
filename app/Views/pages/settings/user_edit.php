<!-- Page Title -->
<?php $title = 'Foydalanuvchini tahrirlash'; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .form-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .form-title i {
        color: #667eea;
        margin-right: 10px;
    }
    
    .form-label {
        font-weight: 500;
        color: #555;
        margin-bottom: 8px;
    }
    
    .form-control, .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 15px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }
    
    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
    
    .btn-cancel {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        padding: 12px 30px;
        border-radius: 8px;
        color: #666;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-cancel:hover {
        background: #e0e0e0;
    }
    
    .user-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .user-info .label {
        color: #666;
        font-size: 12px;
    }
    
    .user-info .value {
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .form-card {
            padding: 20px;
        }
        
        .form-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
        
        .btn-outline-secondary {
            width: 100%;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Page Content -->
<div class="form-card">
    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-user-edit"></i> "<?= htmlspecialchars($user['fio']) ?>" ma'lumotlari
        </div>
        <a href="/new-pos/settings/users" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>
    
    <!-- User Info -->
    <div class="user-info">
        <div class="row">
            <div class="col-md-3">
                <div class="label">ID</div>
                <div class="value">#<?= $user['id'] ?></div>
            </div>
            <div class="col-md-3">
                <div class="label">Oxirgi kirish</div>
                <div class="value"><?= $user['oxirgi_kirish_vaqt'] ? date('d.m.Y H:i', strtotime($user['oxirgi_kirish_vaqt'])) : 'Hali kirmagan' ?></div>
            </div>
            <div class="col-md-3">
                <div class="label">Yaratilgan</div>
                <div class="value"><?= date('d.m.Y', strtotime($user['yaratilgan_vaqt'])) ?></div>
            </div>
            <div class="col-md-3">
                <div class="label">Holat</div>
                <div class="value">
                    <?php if ($user['faol']): ?>
                        <span class="badge bg-success">Faol</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Bloklangan</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <form method="POST" action="/new-pos/settings/users/update/<?= $user['id'] ?>" id="userForm">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="row">
            <!-- F.I.O. -->
            <div class="col-md-6 mb-3">
                <label class="form-label">F.I.O. <span class="text-danger">*</span></label>
                <input type="text" name="fio" class="form-control <?= isset($_SESSION['errors']['fio']) ? 'is-invalid' : '' ?>" 
                       value="<?= isset($_SESSION['old']['fio']) ? htmlspecialchars($_SESSION['old']['fio']) : htmlspecialchars($user['fio']) ?>"
                       required>
                <?php if (isset($_SESSION['errors']['fio'])): ?>
                    <div class="invalid-feedback"><?= implode(', ', $_SESSION['errors']['fio']) ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Login -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Login <span class="text-danger">*</span></label>
                <input type="text" name="login" class="form-control <?= isset($_SESSION['errors']['login']) ? 'is-invalid' : '' ?>" 
                       value="<?= isset($_SESSION['old']['login']) ? htmlspecialchars($_SESSION['old']['login']) : htmlspecialchars($user['login']) ?>"
                       required>
                <?php if (isset($_SESSION['errors']['login'])): ?>
                    <div class="invalid-feedback"><?= implode(', ', $_SESSION['errors']['login']) ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Email -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" 
                       value="<?= isset($_SESSION['old']['email']) ? htmlspecialchars($_SESSION['old']['email']) : htmlspecialchars($user['email'] ?? '') ?>">
            </div>
            
            <!-- Telefon -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Telefon</label>
                <input type="text" name="telefon" class="form-control" 
                       value="<?= isset($_SESSION['old']['telefon']) ? htmlspecialchars($_SESSION['old']['telefon']) : htmlspecialchars($user['telefon'] ?? '') ?>">
            </div>
            
            <!-- Rol -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Rol <span class="text-danger">*</span></label>
                <select name="rol_id" class="form-select" required>
                    <option value="">Rol tanlang</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= $role['id'] == $user['rol_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($role['nomi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Yangi parol (agar o'zgartirish kerak bo'lsa) -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Yangi parol</label>
                <input type="password" name="password" class="form-control" id="password"
                       placeholder="Agar o'zgartirmoqchi bo'lsangiz">
                <small class="text-muted">Parolni o'zgartirish uchun yangi parol kiriting</small>
            </div>
            
            <!-- Faol -->
            <div class="col-12 mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="faol" id="faol" value="1" <?= $user['faol'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="faol">Foydalanuvchi faol</label>
                </div>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="d-flex justify-content-end gap-2">
            <a href="/new-pos/settings/users" class="btn btn-cancel">
                <i class="fas fa-times"></i> Bekor qilish
            </a>
            <button type="submit" class="btn btn-save">
                <i class="fas fa-save"></i> Yangilash
            </button>
        </div>
    </form>
</div>

<?php 
// Clear old data
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>