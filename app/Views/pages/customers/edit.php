<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijozni tahrirlash - POS System</title>
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
            border-radius: 8px;
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
        
        .customer-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .customer-info .label {
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .customer-info .value {
            font-weight: 600;
            color: #333;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
        }
        .stat-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .stat-item .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }
        .stat-item .stat-label {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        .stat-item .stat-debt {
            color: #dc3545;
        }
        
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .warning-box i {
            color: #856404;
            margin-right: 10px;
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
                    <a href="/new-pos/categories" class="nav-link">
                        <i class="fas fa-tags"></i> Kategoriyalar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/subcategories" class="nav-link">
                        <i class="fas fa-folder-open"></i> Subkategoriyalar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/customers" class="nav-link active">
                        <i class="fas fa-users"></i> Mijozlar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/debt" class="nav-link">
                        <i class="fas fa-credit-card"></i> Qarzdorlar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/reports" class="nav-link">
                        <i class="fas fa-chart-bar"></i> Hisobotlar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/settings" class="nav-link">
                        <i class="fas fa-cog"></i> Sozlamalar
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
                    <h4>Mijozni tahrirlash</h4>
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
                        <i class="fas fa-user-edit"></i> "<?= htmlspecialchars($customer['fio']) ?>" ma'lumotlari
                    </div>
                    <a href="/new-pos/customers" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Orqaga
                    </a>
                </div>
                
                <?php
                // Mijoz statistikasini olish
                $debtModel = new \App\Models\Debt();
                $debtInfo = $debtModel->getCustomerDebt($customer['id']);
                $totalDebt = $debtInfo['jami_qarz'] ?? 0;
                
                // Savdolar soni
                $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM savdolar WHERE mijoz_id = ? AND holat = 'YAKUNLANGAN'");
                $stmt->execute([$customer['id']]);
                $salesCount = $stmt->fetch()['count'];
                
                // Oxirgi savdo
                $stmt = $this->db->prepare("SELECT MAX(sotilgan_vaqt) as last FROM savdolar WHERE mijoz_id = ?");
                $stmt->execute([$customer['id']]);
                $lastSale = $stmt->fetch()['last'];
                ?>
                
                <!-- Customer Info -->
                <div class="customer-info">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="label">ID</div>
                            <div class="value">#<?= $customer['id'] ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="label">Qo'shilgan sana</div>
                            <div class="value"><?= date('d.m.Y', strtotime($customer['yaratilgan_vaqt'])) ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="label">Oxirgi yangilanish</div>
                            <div class="value"><?= $customer['yangilangan_vaqt'] ? date('d.m.Y', strtotime($customer['yangilangan_vaqt'])) : '-' ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="label">Holat</div>
                            <div class="value">
                                <?php if ($customer['faol']): ?>
                                    <span class="badge bg-success">Faol</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Faol emas</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value"><?= $salesCount ?></div>
                            <div class="stat-label">Jami savdolar</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value <?= $totalDebt > 0 ? 'stat-debt' : '' ?>">
                                <?= number_format($totalDebt, 0, ',', ' ') ?> so'm
                            </div>
                            <div class="stat-label">Jami qarz</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?= $lastSale ? date('d.m.Y', strtotime($lastSale)) : '-' ?></div>
                            <div class="stat-label">Oxirgi savdo</div>
                        </div>
                    </div>
                </div>
                
                <!-- Warning if has debt -->
                <?php if ($totalDebt > 0): ?>
                <div class="warning-box">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Diqqat!</strong> Bu mijozning <?= number_format($totalDebt, 0, ',', ' ') ?> so'm qarzi bor. Qarzni to'lov qismida ko'rishingiz mumkin.
                    <a href="/new-pos/debt/customer/<?= $customer['id'] ?>" class="alert-link">Qarzni ko'rish →</a>
                </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['flash']['error'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['flash']['error']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['flash']['success'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['flash']['success']); ?>
                <?php endif; ?>
                
                <form method="POST" action="/new-pos/customers/update/<?= $customer['id'] ?>" id="customerForm">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div class="row">
                        <!-- F.I.O. -->
                        <div class="col-md-12 mb-3">
                            <label for="fio" class="form-label">
                                <i class="fas fa-user"></i> F.I.O. <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?= isset($_SESSION['errors']['fio']) ? 'is-invalid' : '' ?>" 
                                   id="fio" 
                                   name="fio" 
                                   value="<?= isset($_SESSION['old']['fio']) ? htmlspecialchars($_SESSION['old']['fio']) : htmlspecialchars($customer['fio']) ?>"
                                   required>
                            <?php if (isset($_SESSION['errors']['fio'])): ?>
                                <div class="invalid-feedback d-block">
                                    <?= implode(', ', $_SESSION['errors']['fio']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Telefon -->
                        <div class="col-md-6 mb-3">
                            <label for="telefon" class="form-label">
                                <i class="fas fa-phone"></i> Telefon raqam
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="telefon" 
                                   name="telefon" 
                                   value="<?= isset($_SESSION['old']['telefon']) ? htmlspecialchars($_SESSION['old']['telefon']) : htmlspecialchars($customer['telefon'] ?? '') ?>"
                                   placeholder="+998901234567">
                        </div>
                        
                        <!-- Manzil -->
                        <div class="col-md-6 mb-3">
                            <label for="manzil" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Manzil
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="manzil" 
                                   name="manzil" 
                                   value="<?= isset($_SESSION['old']['manzil']) ? htmlspecialchars($_SESSION['old']['manzil']) : htmlspecialchars($customer['manzil'] ?? '') ?>"
                                   placeholder="Toshkent sh., Chilonzor t.">
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
                                      placeholder="Mijoz haqida qo'shimcha ma'lumot..."><?= isset($_SESSION['old']['izoh']) ? htmlspecialchars($_SESSION['old']['izoh']) : htmlspecialchars($customer['izoh'] ?? '') ?></textarea>
                        </div>
                        
                        <!-- Faol -->
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="faol" 
                                       name="faol" 
                                       value="1"
                                       <?= (isset($_SESSION['old']['faol']) ? $_SESSION['old']['faol'] : ($customer['faol'] ?? 1)) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="faol">
                                    <i class="fas fa-check-circle text-success"></i> Mijoz faol
                                </label>
                            </div>
                            <small class="text-muted">Faol bo'lmasa, POS da mijoz tanlashda ko'rinmaydi</small>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="/new-pos/customers" class="btn btn-cancel">
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
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Form validation
        document.getElementById('customerForm')?.addEventListener('submit', function(e) {
            const fio = document.getElementById('fio').value.trim();
            
            if (fio.length < 3) {
                e.preventDefault();
                alert('F.I.O. kamida 3 harfdan iborat bo\'lishi kerak');
                return false;
            }
            
            return true;
        });
        
        // Auto close alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Confirm before leaving page with unsaved changes
        let formChanged = false;
        
        document.getElementById('customerForm')?.addEventListener('input', function() {
            formChanged = true;
        });
        
        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
        
        // Reset form changed flag on submit
        document.getElementById('customerForm')?.addEventListener('submit', function() {
            formChanged = false;
        });
    </script>
</body>
</html>
<?php 
// Clear old data
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>