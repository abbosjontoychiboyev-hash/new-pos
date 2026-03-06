<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahsulot qaytarish - POS System</title>
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
        .sidebar-header { padding: 25px 20px; text-align: center; }
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
        
        .return-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .return-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .return-header i {
            font-size: 60px;
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .return-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }
        
        .return-header p {
            color: #666;
        }
        
        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-box input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .search-box input:focus {
            border-color: #667eea;
            outline: none;
        }
        
        .search-box button {
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
        }
        
        .info-box {
            background: #e7f5ff;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .info-box i {
            color: #667eea;
            margin-right: 10px;
        }
        
        .quick-links {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }
        
        .quick-links a {
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 20px;
            color: #666;
            text-decoration: none;
            font-size: 13px;
        }
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
                <li class="nav-item"><a href="/new-pos/returns" class="nav-link active"><i class="fas fa-undo-alt"></i> Qaytarish</a></li>
                <li class="nav-item"><a href="/new-pos/customers" class="nav-link"><i class="fas fa-users"></i> Mijozlar</a></li>
                <li class="nav-item"><a href="/new-pos/debt" class="nav-link"><i class="fas fa-credit-card"></i> Qarzdorlar</a></li>
                <li class="nav-item"><a href="/new-pos/reports" class="nav-link"><i class="fas fa-chart-bar"></i> Hisobotlar</a></li>
                <li class="nav-item"><a href="/new-pos/settings" class="nav-link"><i class="fas fa-cog"></i> Sozlamalar</a></li>
                <li class="nav-item"><a href="/new-pos/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Chiqish</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="top-bar">
                <h4>Mahsulot qaytarish</h4>
                <div class="user-info">
                    <span><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></span>
                </div>
            </div>
            
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['flash']['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['flash']['success'] ?></div>
                <?php unset($_SESSION['flash']['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['flash']['error'] ?></div>
                <?php unset($_SESSION['flash']['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash']['warning'])): ?>
                <div class="alert alert-warning"><?= $_SESSION['flash']['warning'] ?></div>
                <?php unset($_SESSION['flash']['warning']); ?>
            <?php endif; ?>
            
            <!-- Return Card -->
            <div class="return-card">
                <div class="return-header">
                    <i class="fas fa-undo-alt"></i>
                    <h2>Mahsulot qaytarish</h2>
                    <p>Chek raqami orqali savdoni qidiring</p>
                </div>
                
                <form action="/new-pos/returns/search" method="GET">
                    <div class="search-box">
                        <input type="text" 
                               name="receipt" 
                               placeholder="Chek raqamini kiriting (masalan: CHK-20240315-0001)"
                               required>
                        <button type="submit">
                            <i class="fas fa-search"></i> Qidirish
                        </button>
                    </div>
                </form>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <strong>Eslatma:</strong> Faqat yakunlangan savdolarni qaytarish mumkin. Qaytarilgan mahsulotlar omborga qaytariladi.
                </div>
                
                <div class="quick-links">
                    <a href="/new-pos/returns/history"><i class="fas fa-history"></i> Qaytarish tarixi</a>
                    <a href="/new-pos/pos"><i class="fas fa-shopping-cart"></i> Yangi savdo</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>