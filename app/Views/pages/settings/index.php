<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sozlamalar - POS System</title>
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
        
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .settings-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
            border: 2px solid transparent;
        }
        .settings-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border-color: #667eea;
        }
        .settings-card .icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        .settings-card .icon i {
            font-size: 30px;
            color: white;
        }
        .settings-card .title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .settings-card .description {
            color: #666;
            font-size: 14px;
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
                <h4>Sozlamalar</h4>
                <div class="user-info">
                    <span><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></span>
                    <span class="badge bg-info"><?= $_SESSION['user']['rol_nomi'] ?? 'Role' ?></span>
                </div>
            </div>
            
            <!-- Settings Grid -->
            <div class="settings-grid">
                <a href="/new-pos/settings/company" class="settings-card">
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="title">Kompaniya ma'lumotlari</div>
                    <div class="description">Kompaniya nomi, manzili, telefon raqami va boshqa ma'lumotlar</div>
                </a>
                
                <a href="/new-pos/settings/currency" class="settings-card">
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="title">Valyuta sozlamalari</div>
                    <div class="description">Valyuta belgisi, format, o'nlik belgilar va boshqalar</div>
                </a>
                
                <a href="/new-pos/settings/pos" class="settings-card">
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="title">POS sozlamalari</div>
                    <div class="description">Chek formati, avtomatik chop etish, to'lov usullari</div>
                </a>
                
                <a href="/new-pos/settings/users" class="settings-card">
                    <div class="icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="title">Foydalanuvchilar</div>
                    <div class="description">Foydalanuvchilarni boshqarish, rollar va ruxsatlar</div>
                </a>
                
                <a href="/new-pos/settings/profile" class="settings-card">
                    <div class="icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="title">Mening profilim</div>
                    <div class="description">Shaxsiy ma'lumotlar, parolni o'zgartirish</div>
                </a>
                
                <a href="/new-pos/settings/backup" class="settings-card">
                    <div class="icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="title">Zaxiralash</div>
                    <div class="description">Ma'lumotlar bazasini zaxiralash va tiklash</div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>