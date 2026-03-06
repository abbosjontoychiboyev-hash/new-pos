<!-- Page Title -->
<?php $title = 'Mening profilim'; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .profile-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px;
        text-align: center;
        color: white;
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 50%;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid rgba(255,255,255,0.3);
    }
    
    .profile-avatar i {
        font-size: 50px;
        color: #667eea;
    }
    
    .profile-name {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .profile-role {
        font-size: 16px;
        opacity: 0.9;
    }
    
    .profile-body {
        padding: 30px;
    }
    
    .info-row {
        display: flex;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .info-label {
        width: 150px;
        font-weight: 600;
        color: #666;
    }
    
    .info-value {
        flex: 1;
        color: #333;
    }
    
    .tab-pane {
        padding: 20px 0;
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
        .profile-header {
            padding: 20px;
        }
        
        .profile-avatar {
            width: 80px;
            height: 80px;
        }
        
        .profile-avatar i {
            font-size: 40px;
        }
        
        .profile-name {
            font-size: 20px;
        }
        
        .profile-role {
            font-size: 14px;
        }
        
        .profile-body {
            padding: 20px;
        }
        
        .info-row {
            flex-direction: column;
        }
        
        .info-label {
            width: 100%;
            margin-bottom: 5px;
        }
        
        .nav-tabs {
            flex-wrap: wrap;
        }
        
        .nav-tabs .nav-item {
            width: 100%;
            margin-bottom: 5px;
        }
        
        .nav-tabs .nav-link {
            width: 100%;
            text-align: center;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Password strength checker
    document.getElementById('new_password')?.addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('passwordStrength');
        
        if (!strengthBar) return;
        
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
    
    // Password match checker
    document.getElementById('confirm_password')?.addEventListener('input', function() {
        const password = document.getElementById('new_password').value;
        const confirm = this.value;
        const matchMsg = document.getElementById('passwordMatch');
        
        if (password === confirm) {
            matchMsg.innerHTML = '<span class="text-success">Parollar mos</span>';
        } else {
            matchMsg.innerHTML = '<span class="text-danger">Parollar mos emas</span>';
        }
    });
    
    // Form validation
    document.getElementById('passwordForm')?.addEventListener('submit', function(e) {
        const password = document.getElementById('new_password').value;
        const confirm = document.getElementById('confirm_password').value;
        
        if (password !== confirm) {
            e.preventDefault();
            alert('Yangi parollar mos kelmadi!');
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<div class="profile-card">
    <div class="profile-header">
        <div class="profile-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="profile-name"><?= htmlspecialchars($user['fio']) ?></div>
        <div class="profile-role"><?= htmlspecialchars($user['rol_nomi'] ?? 'Foydalanuvchi') ?></div>
    </div>
    
    <div class="profile-body">
        <!-- Tabs -->
        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                    <i class="fas fa-info-circle"></i> Shaxsiy ma'lumotlar
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                    <i class="fas fa-key"></i> Parolni o'zgartirish
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                    <i class="fas fa-history"></i> Faollik tarixi
                </button>
            </li>
        </ul>
        
        <!-- Tab Content -->
        <div class="tab-content" id="profileTabsContent">
            <!-- Shaxsiy ma'lumotlar -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <form method="POST" action="/new-pos/settings/profile/update">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">F.I.O.</label>
                            <input type="text" name="fio" class="form-control" 
                                   value="<?= htmlspecialchars($user['fio']) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Login</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['login']) ?>" readonly disabled>
                            <small class="text-muted">Login o'zgartirib bo'lmaydi</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="telefon" class="form-control" 
                                   value="<?= htmlspecialchars($user['telefon'] ?? '') ?>">
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save"></i> Ma'lumotlarni saqlash
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Parolni o'zgartirish -->
            <div class="tab-pane fade" id="password" role="tabpanel">
                <form method="POST" action="/new-pos/settings/profile/update" id="passwordForm">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div class="row mt-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Joriy parol</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Yangi parol</label>
                            <input type="password" name="new_password" class="form-control" id="new_password" required>
                            <div class="password-strength" id="passwordStrength"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Yangi parol (takror)</label>
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                            <small class="text-muted" id="passwordMatch"></small>
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-key"></i> Parolni yangilash
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Faollik tarixi -->
            <div class="tab-pane fade" id="activity" role="tabpanel">
                <div class="mt-4">
                    <div class="info-row">
                        <div class="info-label">Oxirgi kirish:</div>
                        <div class="info-value">
                            <?= $user['oxirgi_kirish_vaqt'] ? date('d.m.Y H:i:s', strtotime($user['oxirgi_kirish_vaqt'])) : 'Hali kirmagan' ?>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Profil yaratilgan:</div>
                        <div class="info-value">
                            <?= date('d.m.Y H:i:s', strtotime($user['yaratilgan_vaqt'])) ?>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Oxirgi yangilanish:</div>
                        <div class="info-value">
                            <?= date('d.m.Y H:i:s', strtotime($user['yangilangan_vaqt'])) ?>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">Roli:</div>
                        <div class="info-value">
                            <span class="badge bg-info"><?= htmlspecialchars($user['rol_nomi'] ?? 'Foydalanuvchi') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>