<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yangi mahsulot - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; }
        .wrapper { display: flex; }
        
        /* Sidebar */
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
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }
        
        /* Top Bar */
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
        
        /* Form Card */
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
        
        /* Form Elements */
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
        
        /* Price Input Group */
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-weight: 500;
        }
        
        /* Checkbox */
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        /* Buttons */
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
        
        /* Barcode Preview */
        .barcode-preview {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 10px;
            font-family: monospace;
            font-size: 18px;
            letter-spacing: 2px;
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
                    <a href="/new-pos/products" class="nav-link active">
                        <i class="fas fa-box"></i> Mahsulotlar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/categories" class="nav-link">
                        <i class="fas fa-tags"></i> Kategoriyalar
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
                    <h4>Yangi mahsulot qo'shish</h4>
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
                        <i class="fas fa-box"></i> Mahsulot ma'lumotlari
                    </div>
                    <a href="/new-pos/products" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Orqaga
                    </a>
                </div>
                
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['flash']['error'] ?>
                    </div>
                    <?php unset($_SESSION['flash']['error']); ?>
                <?php endif; ?>
                
                <form method="POST" action="/new-pos/products/store" id="productForm">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div class="row">
                        <!-- Shtrix kod -->
                        <div class="col-md-6 mb-3">
                            <label for="shtrix_kod" class="form-label">
                                <i class="fas fa-barcode"></i> Shtrix kod <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control <?= isset($_SESSION['errors']['shtrix_kod']) ? 'is-invalid' : '' ?>" 
                                       id="shtrix_kod" 
                                       name="shtrix_kod" 
                                       value="<?= isset($_SESSION['old']['shtrix_kod']) ? htmlspecialchars($_SESSION['old']['shtrix_kod']) : '' ?>"
                                       placeholder="Masalan: 4780012345678"
                                       required>
                                <button type="button" class="btn btn-outline-secondary" onclick="generateBarcode()">
                                    <i class="fas fa-random"></i>
                                </button>
                            </div>
                            <?php if (isset($_SESSION['errors']['shtrix_kod'])): ?>
                                <div class="invalid-feedback">
                                    <?= implode(', ', $_SESSION['errors']['shtrix_kod']) ?>
                                </div>
                            <?php endif; ?>
                            <small class="text-muted">13 xonali raqam</small>
                        </div>
                        
                        <!-- Nomi -->
                        <div class="col-md-6 mb-3">
                            <label for="nomi" class="form-label">
                                <i class="fas fa-tag"></i> Mahsulot nomi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?= isset($_SESSION['errors']['nomi']) ? 'is-invalid' : '' ?>" 
                                   id="nomi" 
                                   name="nomi" 
                                   value="<?= isset($_SESSION['old']['nomi']) ? htmlspecialchars($_SESSION['old']['nomi']) : '' ?>"
                                   placeholder="Masalan: Coca Cola 1L"
                                   required>
                            <?php if (isset($_SESSION['errors']['nomi'])): ?>
                                <div class="invalid-feedback">
                                    <?= implode(', ', $_SESSION['errors']['nomi']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Kategoriya -->
                        <div class="col-md-6 mb-3">
                            <label for="kategoriya_id" class="form-label">
                                <i class="fas fa-folder"></i> Kategoriya <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?= isset($_SESSION['errors']['kategoriya_id']) ? 'is-invalid' : '' ?>" 
                                    id="kategoriya_id" 
                                    name="kategoriya_id" 
                                    required
                                    onchange="loadSubcategories(this.value)">
                                <option value="">Kategoriya tanlang</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" 
                                        <?= (isset($_SESSION['old']['kategoriya_id']) && $_SESSION['old']['kategoriya_id'] == $category['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['nomi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($_SESSION['errors']['kategoriya_id'])): ?>
                                <div class="invalid-feedback">
                                    <?= implode(', ', $_SESSION['errors']['kategoriya_id']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Subkategoriya -->
                        <div class="col-md-6 mb-3">
                            <label for="subkategoriya_id" class="form-label">
                                <i class="fas fa-folder-open"></i> Subkategoriya
                            </label>
                            <select class="form-select" id="subkategoriya_id" name="subkategoriya_id">
                                <option value="">Subkategoriya tanlang</option>
                            </select>
                        </div>
                        
                        <!-- Birlik -->
                        <div class="col-md-6 mb-3">
                            <label for="birlik" class="form-label">
                                <i class="fas fa-balance-scale"></i> Birlik <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?= isset($_SESSION['errors']['birlik']) ? 'is-invalid' : '' ?>" 
                                    id="birlik" 
                                    name="birlik" 
                                    required>
                                <option value="dona" <?= (isset($_SESSION['old']['birlik']) && $_SESSION['old']['birlik'] == 'dona') ? 'selected' : '' ?>>Dona</option>
                                <option value="kg" <?= (isset($_SESSION['old']['birlik']) && $_SESSION['old']['birlik'] == 'kg') ? 'selected' : '' ?>>Kilogramm (kg)</option>
                                <option value="litr" <?= (isset($_SESSION['old']['birlik']) && $_SESSION['old']['birlik'] == 'litr') ? 'selected' : '' ?>>Litr</option>
                                <option value="quti" <?= (isset($_SESSION['old']['birlik']) && $_SESSION['old']['birlik'] == 'quti') ? 'selected' : '' ?>>Quti</option>
                                <option value="paket" <?= (isset($_SESSION['old']['birlik']) && $_SESSION['old']['birlik'] == 'paket') ? 'selected' : '' ?>>Paket</option>
                            </select>
                            <?php if (isset($_SESSION['errors']['birlik'])): ?>
                                <div class="invalid-feedback">
                                    <?= implode(', ', $_SESSION['errors']['birlik']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Kelish narxi -->
                        <div class="col-md-6 mb-3">
                            <label for="kelish_narxi" class="form-label">
                                <i class="fas fa-arrow-down"></i> Kelish narxi <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control <?= isset($_SESSION['errors']['kelish_narxi']) ? 'is-invalid' : '' ?>" 
                                       id="kelish_narxi" 
                                       name="kelish_narxi" 
                                       value="<?= isset($_SESSION['old']['kelish_narxi']) ? $_SESSION['old']['kelish_narxi'] : '' ?>"
                                       placeholder="0"
                                       onkeyup="this.value = this.value.replace(/[^0-9]/g, '')"
                                       required>
                                <span class="input-group-text">so'm</span>
                                <?php if (isset($_SESSION['errors']['kelish_narxi'])): ?>
                                    <div class="invalid-feedback">
                                        <?= implode(', ', $_SESSION['errors']['kelish_narxi']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Sotish narxi -->
                        <div class="col-md-6 mb-3">
                            <label for="sotish_narxi" class="form-label">
                                <i class="fas fa-arrow-up"></i> Sotish narxi <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control <?= isset($_SESSION['errors']['sotish_narxi']) ? 'is-invalid' : '' ?>" 
                                       id="sotish_narxi" 
                                       name="sotish_narxi" 
                                       value="<?= isset($_SESSION['old']['sotish_narxi']) ? $_SESSION['old']['sotish_narxi'] : '' ?>"
                                       placeholder="0"
                                       onkeyup="this.value = this.value.replace(/[^0-9]/g, '')"
                                       required>
                                <span class="input-group-text">so'm</span>
                                <?php if (isset($_SESSION['errors']['sotish_narxi'])): ?>
                                    <div class="invalid-feedback">
                                        <?= implode(', ', $_SESSION['errors']['sotish_narxi']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Foyda ko'rsatkichi -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-chart-line"></i> Foyda
                            </label>
                            <div class="form-control bg-light" id="foyda_display">
                                0 so'm (0%)
                            </div>
                        </div>
                        
                        <!-- Miqdor -->
                        <div class="col-md-6 mb-3">
                            <label for="miqdor" class="form-label">
                                <i class="fas fa-cubes"></i> Boshlang'ich miqdor
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="miqdor" 
                                   name="miqdor" 
                                   value="<?= isset($_SESSION['old']['miqdor']) ? $_SESSION['old']['miqdor'] : '0' ?>"
                                   min="0">
                            <small class="text-muted">0 bo'lsa, keyinroq qo'shishingiz mumkin</small>
                        </div>
                        
                        <!-- Minimal miqdor -->
                        <div class="col-md-6 mb-3">
                            <label for="minimal_miqdor" class="form-label">
                                <i class="fas fa-exclamation-triangle"></i> Minimal miqdor
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="minimal_miqdor" 
                                   name="minimal_miqdor" 
                                   value="<?= isset($_SESSION['old']['minimal_miqdor']) ? $_SESSION['old']['minimal_miqdor'] : '5' ?>"
                                   min="0">
                            <small class="text-muted">Bu miqdordan kam bo'lsa, ogohlantirish chiqadi</small>
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
                                    <i class="fas fa-check-circle text-success"></i> Mahsulot faol
                                </label>
                            </div>
                            <small class="text-muted">Faol bo'lmasa, savdoda ko'rinmaydi</small>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="/new-pos/products" class="btn btn-cancel">
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
        // Barcode generator
        function generateBarcode() {
            let barcode = '';
            for (let i = 0; i < 13; i++) {
                barcode += Math.floor(Math.random() * 10);
            }
            document.getElementById('shtrix_kod').value = barcode;
        }
        
        // Load subcategories via AJAX
        function loadSubcategories(categoryId) {
            if (!categoryId) {
                document.getElementById('subkategoriya_id').innerHTML = '<option value="">Subkategoriya tanlang</option>';
                return;
            }
            
            fetch('/new-pos/api/get-subcategories?category_id=' + categoryId)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="">Subkategoriya tanlang</option>';
                    data.forEach(sub => {
                        options += `<option value="${sub.id}">${sub.nomi}</option>`;
                    });
                    document.getElementById('subkategoriya_id').innerHTML = options;
                });
        }
        
        // Calculate profit
        function calculateProfit() {
            let kelish = parseFloat(document.getElementById('kelish_narxi').value.replace(/[^0-9]/g, '')) || 0;
            let sotish = parseFloat(document.getElementById('sotish_narxi').value.replace(/[^0-9]/g, '')) || 0;
            
            if (kelish > 0 && sotish > 0) {
                let foyda = sotish - kelish;
                let foiz = (foyda / kelish * 100).toFixed(1);
                
                let display = document.getElementById('foyda_display');
                display.innerHTML = `${foyda.toLocaleString()} so'm (${foiz}%)`;
                
                if (foyda < 0) {
                    display.classList.add('text-danger');
                } else {
                    display.classList.remove('text-danger');
                }
            }
        }
        
        document.getElementById('kelish_narxi').addEventListener('keyup', calculateProfit);
        document.getElementById('sotish_narxi').addEventListener('keyup', calculateProfit);
        
        // Form submit validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            let kelish = parseFloat(document.getElementById('kelish_narxi').value.replace(/[^0-9]/g, '')) || 0;
            let sotish = parseFloat(document.getElementById('sotish_narxi').value.replace(/[^0-9]/g, '')) || 0;
            
            if (sotish < kelish) {
                if (!confirm('Sotish narxi kelish narxidan past. Davom etishni xohlaysizmi?')) {
                    e.preventDefault();
                }
            }
        });
        
        // Load subcategories if category already selected
        <?php if (isset($_SESSION['old']['kategoriya_id']) && $_SESSION['old']['kategoriya_id']): ?>
            loadSubcategories(<?= $_SESSION['old']['kategoriya_id'] ?>);
        <?php endif; ?>
    </script>
</body>
</html>
<?php 
// Clear old data
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>