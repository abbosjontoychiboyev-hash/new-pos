<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yangi foydalanuvchi - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; }
        .wrapper { display: flex; }
        
        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
        }
        .sidebar-header { padding: 25px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-header h3 { font-size: 24px; font-weight: 700; }
        .nav-menu { padding: 20px 0; list-style: none; }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-left: 3px solid transparent;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }
        .nav-link i { width: 25px; margin-right: 10px; }
        
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }
        
        .top-bar {
            background: white;
            border-radius: 12px;
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
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
        }
        
        .btn-cancel {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            padding: 12px 30px;
            border-radius: 8px;
            color: #666;
            font-weight: 600;
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
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>POS Magazin</h3>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="/new-pos/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item"><a href="/new-pos/pos" class="nav-link"><i class="fas fa-shopping-cart"></i> POS</a></li>
                <li class="nav-item"><a href="/new-pos/products" class="nav-link"><i class="fas fa-box"></i> Mahsulotlar</a></li>
                <li class="nav-item"><a href="/new-pos/categories" class="nav-link"><i class="fas fa-tags"></i> Kategoriyalar</a></li>
                <li class="nav-item"><a href="/new-pos/customers" class="nav-link"><i class="fas fa-users"></i> Mijozlar</a></li>
                <li class="nav-item"><a href="/new-pos/debt" class="nav-link"><i class="fas fa-credit-card"></i> Qarzdorlar</a></li>
                <li class="nav-item"><a href="/new-pos/reports" class="nav-link"><i class="fas fa-chart-bar"></i> Hisobotlar</a></li>
                <li class="nav-item"><a href="/new-pos/settings" class="nav-link active"><i class="fas fa-cog"></i> Sozlamalar</a></li>
                <li class="nav-item"><a href="/new-pos/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Chiqish</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <h4>Yangi foydalanuvchi qo'shish</h4>
                <div class="user-info">
                    <span><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></span>
                </div>
            </div>
            
            <!-- Form Card -->
            <div class="form-card">
                <div class="form-header">
                    <div class="form-title">
                        <i class="fas fa-user-plus"></i> Foydalanuvchi ma'lumotlari
                    </div>
                    <a href="/new-pos/settings/users" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Orqaga
                    </a>
                </div>
                
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['flash']['error'] ?></div>
                    <?php unset($_SESSION['flash']['error']); ?>
                <?php endif; ?>
                
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
        </div>
    </div>
    
    <script>
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
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
</body>
</html>
<?php 
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>