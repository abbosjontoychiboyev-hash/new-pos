<!-- Page Title -->
<?php $title = 'Yangi foydalanuvchi qo\'shish'; ?>

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
    
    .form-control.is-invalid {
        border-color: #dc3545;
    }
    
    .invalid-feedback {
        font-size: 13px;
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
    
    .password-strength {
        height: 5px;
        margin-top: 5px;
        border-radius: 3px;
        transition: all 0.3s;
    }
    
    .strength-weak { background: #dc3545; width: 33%; }
    .strength-medium { background: #ffc107; width: 66%; }
    .strength-strong { background: #28a745; width: 100%; }
    
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

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Password strength checker
    document.getElementById('password')?.addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('passwordStrength');
        
        let strength = 0;
        
        if (password.length >= 6) strength++;
        if (password.match(/[a-z]+/)) strength++;
        if (password.match(/[A-Z]+/)) strength++;
        if (password.match(/[0-9]+/)) strength++;
        if (password.match(/[$@#&!]+/)) strength++;
        
        strengthBar.className = 'password-strength';
        
        if (password.length === 0) {
            strengthBar.style.width = '0';
        } else if (strength < 2) {
            strengthBar.classList.add('strength-weak');
        } else if (strength < 4) {
            strengthBar.classList.add('strength-medium');
        } else {
            strengthBar.classList.add('strength-strong');
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<div class="form-card">
    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-user-plus"></i> Foydalanuvchi ma'lumotlari
        </div>
        <a href="/new-pos/settings/users" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>
    
    <form method="POST" action="/new-pos/settings/users/store" id="userForm">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="row">
            <!-- F.I.O. -->
            <div class="col-md-6 mb-3">
                <label class="form-label">F.I.O. <span class="text-danger">*</span></label>
                <input type="text" name="fio" class="form-control <?= isset($_SESSION['errors']['fio']) ? 'is-invalid' : '' ?>" 
                       value="<?= isset($_SESSION['old']['fio']) ? htmlspecialchars($_SESSION['old']['fio']) : '' ?>"
                       placeholder="To'liq ism familiya" required>
                <?php if (isset($_SESSION['errors']['fio'])): ?>
                    <div class="invalid-feedback"><?= implode(', ', $_SESSION['errors']['fio']) ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Login -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Login <span class="text-danger">*</span></label>
                <input type="text" name="login" class="form-control <?= isset($_SESSION['errors']['login']) ? 'is-invalid' : '' ?>" 
                       value="<?= isset($_SESSION['old']['login']) ? htmlspecialchars($_SESSION['old']['login']) : '' ?>"
                       placeholder="Login nomi" required>
                <?php if (isset($_SESSION['errors']['login'])): ?>
                    <div class="invalid-feedback"><?= implode(', ', $_SESSION['errors']['login']) ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Email -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" 
                       value="<?= isset($_SESSION['old']['email']) ? htmlspecialchars($_SESSION['old']['email']) : '' ?>"
                       placeholder="user@example.com">
            </div>
            
            <!-- Telefon -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Telefon</label>
                <input type="text" name="telefon" class="form-control" 
                       value="<?= isset($_SESSION['old']['telefon']) ? htmlspecialchars($_SESSION['old']['telefon']) : '' ?>"
                       placeholder="+998901234567">
            </div>
            
            <!-- Rol -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Rol <span class="text-danger">*</span></label>
                <select name="rol_id" class="form-select" required>
                    <option value="">Rol tanlang</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= (isset($_SESSION['old']['rol_id']) && $_SESSION['old']['rol_id'] == $role['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($role['nomi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Parol -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Parol <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control <?= isset($_SESSION['errors']['password']) ? 'is-invalid' : '' ?>" 
                       id="password" required>
                <div class="password-strength" id="passwordStrength"></div>
                <?php if (isset($_SESSION['errors']['password'])): ?>
                    <div class="invalid-feedback"><?= implode(', ', $_SESSION['errors']['password']) ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Faol -->
            <div class="col-12 mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="faol" id="faol" value="1" checked>
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
                <i class="fas fa-save"></i> Saqlash
            </button>
        </div>
    </form>
</div>

<?php 
// Clear old data
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>