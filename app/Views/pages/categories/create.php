<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yangi kategoriya - POS System</title>
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
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
        }
        .sidebar-header { padding: 25px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-header h3 { font-size: 24px; font-weight: 700; margin: 0; color: white; }
        .sidebar-header p { font-size: 12px; opacity: 0.8; margin: 5px 0 0; }
        .nav-menu { padding: 20px 0; list-style: none; }
        .nav-item { margin: 5px 0; }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }
        .nav-link i { width: 25px; font-size: 16px; margin-right: 10px; text-align: center; }
        
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
        .page-title h4 { font-size: 20px; font-weight: 600; color: #333; margin: 0; }
        .user-info { display: flex; align-items: center; gap: 10px; }
        
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
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s;
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
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 10px;
            color: white;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.3);
        }
        .btn-cancel {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 10px;
            color: #666;
        }
        .btn-cancel:hover {
            background: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>POS Magazin</h3>
                <p>Savdo boshqaruvi</p>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="/new-pos/dashboard" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/pos" class="nav-link">
                        <i class="fas fa-shopping-cart"></i> POS (Savdo)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/products" class="nav-link">
                        <i class="fas fa-box"></i> Mahsulotlar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/categories" class="nav-link active">
                        <i class="fas fa-tags"></i> Kategoriyalar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/subcategories" class="nav-link">
                        <i class="fas fa-folder-open"></i> Subkategoriyalar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/customers" class="nav-link">
                        <i class="fas fa-users"></i> Mijozlar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/reports" class="nav-link">
                        <i class="fas fa-chart-bar"></i> Hisobotlar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/logout" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Chiqish
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="page-title">
                    <h4>Yangi kategoriya qo'shish</h4>
                </div>
                <div class="user-info">
                    <span><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></span>
                    <span class="badge bg-info"><?= $_SESSION['user']['rol_nomi'] ?? 'Role' ?></span>
                </div>
            </div>
            
            <!-- Form Card -->
            <div class="form-card">
                <div class="form-header">
                    <div class="form-title">
                        <i class="fas fa-tag"></i> Kategoriya ma'lumotlari
                    </div>
                    <a href="/new-pos/categories" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Orqaga
                    </a>
                </div>
                
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['flash']['error'] ?>
                    </div>
                    <?php unset($_SESSION['flash']['error']); ?>
                <?php endif; ?>
                
                <form method="POST" action="/new-pos/categories/store" id="categoryForm">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div class="row">
                        <!-- Nomi -->
                        <div class="col-md-12 mb-3">
                            <label for="nomi" class="form-label">
                                <i class="fas fa-tag"></i> Kategoriya nomi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?= isset($_SESSION['errors']['nomi']) ? 'is-invalid' : '' ?>" 
                                   id="nomi" 
                                   name="nomi" 
                                   value="<?= isset($_SESSION['old']['nomi']) ? htmlspecialchars($_SESSION['old']['nomi']) : '' ?>"
                                   placeholder="Masalan: Ichimliklar"
                                   required>
                            <?php if (isset($_SESSION['errors']['nomi'])): ?>
                                <div class="invalid-feedback d-block">
                                    <?= implode(', ', $_SESSION['errors']['nomi']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Izoh -->
                        <div class="col-md-12 mb-3">
                            <label for="izoh" class="form-label">
                                <i class="fas fa-align-left"></i> Izoh
                            </label>
                            <textarea class="form-control" 
                                      id="izoh" 
                                      name="izoh" 
                                      rows="3" 
                                      placeholder="Kategoriya haqida qisqacha ma'lumot..."><?= isset($_SESSION['old']['izoh']) ? htmlspecialchars($_SESSION['old']['izoh']) : '' ?></textarea>
                        </div>
                        
                        <!-- Tartib raqami -->
                        <div class="col-md-6 mb-3">
                            <label for="tartib" class="form-label">
                                <i class="fas fa-sort-numeric-down"></i> Tartib raqami
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="tartib" 
                                   name="tartib" 
                                   value="<?= isset($_SESSION['old']['tartib']) ? $_SESSION['old']['tartib'] : '0' ?>"
                                   min="0">
                            <small class="text-muted">Kichik raqamlar oldin ko'rsatiladi</small>
                        </div>
                        
                        <!-- Faol -->
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="faol" 
                                       name="faol" 
                                       value="1"
                                       <?= !isset($_SESSION['old']['faol']) || $_SESSION['old']['faol'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="faol">
                                    <i class="fas fa-check-circle text-success"></i> Kategoriya faol
                                </label>
                            </div>
                            <small class="text-muted">Faol bo'lmasa, mahsulot qo'shishda ko'rinmaydi</small>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="/new-pos/categories" class="btn btn-cancel">
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
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Form validation
        document.getElementById('categoryForm')?.addEventListener('submit', function(e) {
            let nomi = document.getElementById('nomi').value.trim();
            
            if (nomi.length < 2) {
                e.preventDefault();
                alert('Kategoriya nomi kamida 2 harfdan iborat bo\'lishi kerak');
            }
        });
        
        // Auto close alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
<?php 
// Clear old data
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>