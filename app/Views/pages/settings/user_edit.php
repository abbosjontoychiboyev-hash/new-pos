<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foydalanuvchini tahrirlash - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Same styles as user_create.php */
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
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar (same) -->
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
                <h4>Foydalanuvchini tahrirlash</h4>
                <div class="user-info">
                    <span><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></span>
                </div>
            </div>
            
            <!-- Form Card -->
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
                
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['flash']['error'] ?></div>
                    <?php unset($_SESSION['flash']['error']); ?>
                <?php endif; ?>
                
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
        </div>
    </div>
</body>
</html>
<?php 
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>