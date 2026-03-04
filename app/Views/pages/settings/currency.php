<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valyuta sozlamalari - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Same styles as company.php */
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
        
        .preview-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .preview-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .preview-amount {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar (same as before) -->
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
                <h4>Valyuta sozlamalari</h4>
                <div class="user-info">
                    <span><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></span>
                </div>
            </div>
            
            <!-- Form Card -->
            <div class="form-card">
                <div class="form-header">
                    <div class="form-title">
                        <i class="fas fa-money-bill-wave"></i> Valyuta formati
                    </div>
                    <a href="/new-pos/settings" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Orqaga
                    </a>
                </div>
                
                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['flash']['success'] ?></div>
                    <?php unset($_SESSION['flash']['success']); ?>
                <?php endif; ?>
                
                <form method="POST" action="/new-pos/settings/currency/save" id="currencyForm">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div class="row">
                        <!-- Valyuta nomi -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valyuta nomi</label>
                            <input type="text" name="currency_name" class="form-control" 
                                   value="<?= htmlspecialchars($currency['currency_name'] ?? 'so\'m') ?>"
                                   placeholder="so'm" id="currencyName">
                        </div>
                        
                        <!-- Valyuta belgisi -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valyuta belgisi</label>
                            <input type="text" name="currency_symbol" class="form-control" 
                                   value="<?= htmlspecialchars($currency['currency_symbol'] ?? 'so\'m') ?>"
                                   placeholder="so'm" id="currencySymbol">
                        </div>
                        
                        <!-- Valyuta joylashuvi -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valyuta belgisi joylashuvi</label>
                            <select name="currency_position" class="form-select" id="currencyPosition">
                                <option value="left" <?= ($currency['currency_position'] ?? '') == 'left' ? 'selected' : '' ?>>Chapda (so'm 1000)</option>
                                <option value="right" <?= ($currency['currency_position'] ?? 'right') == 'right' ? 'selected' : '' ?>>O'ngda (1000 so'm)</option>
                            </select>
                        </div>
                        
                        <!-- O'nlik xonalar -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">O'nlik xonalar soni</label>
                            <select name="decimal_places" class="form-select" id="decimalPlaces">
                                <option value="0" <?= ($currency['decimal_places'] ?? 0) == 0 ? 'selected' : '' ?>>0 (1000)</option>
                                <option value="1" <?= ($currency['decimal_places'] ?? 0) == 1 ? 'selected' : '' ?>>1 (1000.0)</option>
                                <option value="2" <?= ($currency['decimal_places'] ?? 0) == 2 ? 'selected' : '' ?>>2 (1000.00)</option>
                            </select>
                        </div>
                        
                        <!-- Minglik ajratuvchi -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minglik ajratuvchi</label>
                            <select name="thousand_separator" class="form-select" id="thousandSeparator">
                                <option value=" " <?= ($currency['thousand_separator'] ?? ' ') == ' ' ? 'selected' : '' ?>>Bo'sh joy (1 000)</option>
                                <option value="," <?= ($currency['thousand_separator'] ?? ' ') == ',' ? 'selected' : '' ?>>Vergul (1,000)</option>
                                <option value="." <?= ($currency['thousand_separator'] ?? ' ') == '.' ? 'selected' : '' ?>>Nuqta (1.000)</option>
                                <option value="" <?= ($currency['thousand_separator'] ?? ' ') == '' ? 'selected' : '' ?>>Ajratuvchi yo'q (1000)</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Preview -->
                    <div class="preview-box">
                        <div class="preview-title">Namuna:</div>
                        <div class="preview-amount" id="previewAmount">1 234 567 so'm</div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="/new-pos/settings" class="btn btn-cancel">
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
        function updatePreview() {
            const symbol = document.getElementById('currencySymbol').value || 'so\'m';
            const position = document.getElementById('currencyPosition').value;
            const decimalPlaces = parseInt(document.getElementById('decimalPlaces').value);
            const separator = document.getElementById('thousandSeparator').value;
            
            let amount = 1234567;
            let formatted = amount.toString();
            
            // Add thousand separators
            if (separator !== '') {
                formatted = formatted.replace(/\B(?=(\d{3})+(?!\d))/g, separator);
            }
            
            // Add decimals
            if (decimalPlaces > 0) {
                formatted += '.' + '0'.repeat(decimalPlaces);
            }
            
            // Add currency symbol
            if (position === 'left') {
                formatted = symbol + ' ' + formatted;
            } else {
                formatted = formatted + ' ' + symbol;
            }
            
            document.getElementById('previewAmount').textContent = formatted;
        }
        
        // Add event listeners
        document.getElementById('currencySymbol').addEventListener('input', updatePreview);
        document.getElementById('currencyPosition').addEventListener('change', updatePreview);
        document.getElementById('decimalPlaces').addEventListener('change', updatePreview);
        document.getElementById('thousandSeparator').addEventListener('change', updatePreview);
        
        // Initial preview
        updatePreview();
    </script>
</body>
</html>