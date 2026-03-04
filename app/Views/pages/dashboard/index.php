<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .wrapper {
            display: flex;
            width: 100%;
        }
        
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            color: white;
            transition: all 0.3s;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
        }
        
        .sidebar .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .sidebar-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        
        .sidebar .sidebar-header p {
            margin: 5px 0 0;
            font-size: 12px;
            opacity: 0.8;
        }
        
        .sidebar .nav-menu {
            padding: 20px 0;
        }
        
        .sidebar .nav-item {
            list-style: none;
        }
        
        .sidebar .nav-link {
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        
        .navbar-top {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-top .user-info {
            display: flex;
            align-items: center;
        }
        
        .navbar-top .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .navbar-top .user-info .user-name {
            font-weight: 600;
            color: #333;
        }
        
        .navbar-top .user-info .user-role {
            font-size: 12px;
            color: #666;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
            border-left: 4px solid;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.primary { border-left-color: var(--primary-color); }
        .stat-card.success { border-left-color: var(--success-color); }
        .stat-card.info { border-left-color: var(--info-color); }
        .stat-card.warning { border-left-color: var(--warning-color); }
        
        .stat-card .stat-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-card .stat-desc {
            font-size: 12px;
            color: #999;
        }
        
        .stat-card i {
            font-size: 48px;
            color: #ddd;
            position: absolute;
            right: 20px;
            top: 20px;
            opacity: 0.5;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #eef2f6;
            padding: 15px 20px;
            font-weight: 600;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .table thead th {
            border-top: none;
            border-bottom: 2px solid #eef2f6;
            font-weight: 600;
            color: #666;
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .badge.bg-success { background: #d4edda !important; color: #155724; }
        .badge.bg-warning { background: #fff3cd !important; color: #856404; }
        .badge.bg-danger { background: #f8d7da !important; color: #721c24; }
        .badge.bg-info { background: #d1ecf1 !important; color: #0c5460; }
        
        .btn-logout {
            background: none;
            border: 1px solid #e0e0e0;
            padding: 8px 15px;
            border-radius: 5px;
            color: #666;
            transition: all 0.3s;
        }
        
        .btn-logout:hover {
            background: #f8f9fa;
            border-color: #999;
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
                    <a href="/new-pos/dashboard" class="nav-link active">
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
        
        <!-- Content -->
        <div class="content">
            <!-- Top navbar -->
            <div class="navbar-top">
                <div class="page-title">
                    <h4 class="mb-0">Dashboard</h4>
                </div>
                <div class="user-info">
                    <div class="me-3 text-end">
                        <div class="user-name"><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></div>
                        <div class="user-role"><?= $_SESSION['user']['rol_nomi'] ?? 'Role' ?></div>
                    </div>
                    <div class="user-avatar">
                        <i class="fas fa-user-circle fa-2x" style="color: #4e73df;"></i>
                    </div>
                </div>
            </div>
            
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card primary position-relative">
                        <div class="stat-title">Bugungi savdo</div>
                        <div class="stat-value">0 so'm</div>
                        <div class="stat-desc">+0% o'tgan kunga nisbatan</div>
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card success position-relative">
                        <div class="stat-title">Bugungi foyda</div>
                        <div class="stat-value">0 so'm</div>
                        <div class="stat-desc">+0% o'tgan kunga nisbatan</div>
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card info position-relative">
                        <div class="stat-title">Mahsulotlar</div>
                        <div class="stat-value">0</div>
                        <div class="stat-desc">Jami mahsulotlar soni</div>
                        <i class="fas fa-box"></i>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="stat-card warning position-relative">
                        <div class="stat-title">Kam qolgan</div>
                        <div class="stat-value">0</div>
                        <div class="stat-desc">Minimal miqdordan kam</div>
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            
            <!-- Charts and Tables -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-2"></i> Oxirgi 7 kunlik savdo
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-tag me-2"></i> Top kategoriyalar
                        </div>
                        <div class="card-body">
                            <canvas id="categoryChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-box me-2"></i> Kam qolgan mahsulotlar
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i> Hozircha ma'lumot yo'q
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-clock me-2"></i> Oxirgi savdolar
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i> Hozircha savdo yo'q
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-4">
                <small class="text-muted">© 2024 POS Magazin. Barcha huquqlar himoyalangan.</small>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Sales Chart
        const ctx1 = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Dush', 'Sesh', 'Chor', 'Pay', 'Jum', 'Shan', 'Yak'],
                datasets: [{
                    label: 'Savdo summasi',
                    data: [0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
        
        // Category Chart
        const ctx2 = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Ichimliklar', 'Qandolat', 'Non', 'Sut', 'Go\'sht'],
                datasets: [{
                    data: [0, 0, 0, 0, 0],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>