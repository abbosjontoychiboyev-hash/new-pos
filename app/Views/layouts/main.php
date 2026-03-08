<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'POS System' ?></title>
    
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="/new-pos/public/assets/css/responsive.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/new-pos/public/assets/css/style.css">
    
    <!-- Page Specific CSS -->
    <?php if (isset($extraCss)): ?>
        <?= $extraCss ?>
    <?php endif; ?>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; }
        .wrapper { display: flex; }
        
        /* Sidebar - ORIGINAL */
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
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header { 
            padding: 25px 20px; 
            text-align: center; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
        }
        .sidebar-header h3 { 
            font-size: 24px; 
            font-weight: 700; 
            margin: 0; 
            color: white; 
        }
        .sidebar-header p { 
            font-size: 12px; 
            opacity: 0.8; 
            margin: 5px 0 0; 
        }
        
        .nav-menu { 
            padding: 20px 0; 
            list-style: none; 
        }
        .nav-item { 
            margin: 5px 0; 
        }
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
        .nav-link i { 
            width: 25px; 
            font-size: 16px; 
            margin-right: 10px; 
            text-align: center; 
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;  /* sidebar eniga teng */
            padding: 20px;
            width: calc(100% - 260px);  /* Qo'shimcha - to'liq enni hisoblash */
            min-height: 100vh;
            background: #f4f6f9;
        }
        
        

        /* Top Bar - ORIGINAL */
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
        .page-title h4 { 
            font-size: 20px; 
            font-weight: 600; 
            color: #333; 
            margin: 0; 
        }
        .user-info { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
        }
        .user-details { 
            text-align: right; 
        }
        .user-name { 
            font-weight: 600; 
            color: #333; 
            font-size: 15px; 
        }
        .user-role { 
            font-size: 12px; 
            color: #666; 
        }
        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        /* Flash Messages */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger { background: #f8d7da; color: #721c24; }
        .alert-warning { background: #fff3cd; color: #856404; }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            color: #666;
            font-size: 13px;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3><?= htmlspecialchars($companyName ?? 'POS Magazin') ?></h3>
                <p>Savdo boshqaruvi</p>
            </div>
            
            <ul class="nav-menu">
                <?php if ($_SESSION['user']['rol_nomi'] == 'Admin'): ?>
                <!-- Dashboard faqat admin -->
                <li class="nav-item">
                    <a href="/new-pos/dashboard" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <!-- POS hamma ko'rishi mumkin -->
                <li class="nav-item">
                    <a href="/new-pos/pos" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'pos') !== false ? 'active' : '' ?>">
                        <i class="fas fa-shopping-cart"></i> <span>POS (Savdo)</span>
                    </a>
                </li>
                
                <!-- Mahsulotlar – kassir ham qo'sha oladi -->
                <li class="nav-item">
                    <a href="/new-pos/products" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'products') !== false ? 'active' : '' ?>">
                        <i class="fas fa-box"></i> <span>Mahsulotlar</span>
                    </a>
                </li>
                
                <!-- Kategoriyalar -->
                <li class="nav-item">
                    <a href="/new-pos/categories" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'categories') !== false ? 'active' : '' ?>">
                        <i class="fas fa-tags"></i> <span>Kategoriyalar</span>
                    </a>
                </li>
                
                <!-- Subkategoriyalar -->
                <li class="nav-item">
                    <a href="/new-pos/subcategories" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'subcategories') !== false ? 'active' : '' ?>">
                        <i class="fas fa-folder-open"></i> <span>Subkategoriyalar</span>
                    </a>
                </li>
                
                <!-- Mijozlar – kassirga ruxsat -->
                <li class="nav-item">
                    <a href="/new-pos/customers" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'customers') !== false ? 'active' : '' ?>">
                        <i class="fas fa-users"></i> <span>Mijozlar</span>
                    </a>
                </li>
                
                <!-- Qarzdorlar – endi kassirga ham ruxsat -->
                <li class="nav-item">
                    <a href="/new-pos/debt" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'debt') !== false ? 'active' : '' ?>">
                        <i class="fas fa-credit-card"></i> <span>Qarzdorlar</span>
                    </a>
                </li>
                
                <!-- Diller (Yetkazib beruvchilar) – kassirga ruxsat -->
                <li class="nav-item">
                    <a href="/new-pos/yetkazib" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'yetkazib') !== false ? 'active' : '' ?>">
                        <i class="fas fa-truck"></i> <span>Dillerlar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/kirim" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'kirim') !== false ? 'active' : '' ?>">
                        <i class="fas fa-download"></i> <span>Kirimlar</span>
                    </a>
                </li>
                <!-- Qaytarish – kassirga ruxsat -->
                <li class="nav-item">
                    <a href="/new-pos/returns" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'returns') !== false ? 'active' : '' ?>">
                        <i class="fas fa-undo-alt"></i> <span>Qaytarish</span>
                    </a>
                </li>
                
                <!-- Hisobotlar – faqat admin -->
                <?php if ($_SESSION['user']['rol_nomi'] == 'Admin'): ?>
                <li class="nav-item">
                    <a href="/new-pos/reports" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'reports') !== false ? 'active' : '' ?>">
                        <i class="fas fa-chart-bar"></i> <span>Hisobotlar</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <!-- Sozlamalar – faqat admin (profilni hisobga olmaganda) -->
                <?php if ($_SESSION['user']['rol_nomi'] == 'Admin'): ?>
                <li class="nav-item">
                    <a href="/new-pos/settings" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'settings') !== false ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i> <span>Sozlamalar</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <!-- Profil – hamma o'z profiliga kirishi mumkin -->
                <li class="nav-item">
                    <a href="/new-pos/settings/profile" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'profile') !== false ? 'active' : '' ?>">
                        <i class="fas fa-user-circle"></i> <span>Mening profilim</span>
                    </a>
                </li>
                
                <li class="nav-item divider">
                    <hr>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/logout" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> <span>Chiqish</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle d-md-none" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <script>
            function toggleSidebar() {
                document.querySelector('.sidebar').classList.toggle('show');
            }
        </script>
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="page-title">
                    <h4><?= $title ?? 'Dashboard' ?></h4>
                </div>
                <div class="user-info">
                    <div class="user-details">
                        <div class="user-name"><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></div>
                        <div class="user-role"><?= $_SESSION['user']['rol_nomi'] ?? 'Role' ?></div>
                    </div>
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
            
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['flash']['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['flash']['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash']['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['flash']['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash']['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash']['warning'])): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?= $_SESSION['flash']['warning'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash']['warning']); ?>
            <?php endif; ?>
            
            <!-- Page Content -->
            <div class="content-wrapper">
                <?= $content ?? '' ?>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p>© <?= date('Y') ?> POS Magazin. Barcha huquqlar himoyalangan.</p>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Page Specific JS -->
    <?php if (isset($extraJs)): ?>
        <?= $extraJs ?>
    <?php endif; ?>
    
    <script>
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