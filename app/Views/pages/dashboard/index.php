<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s;
            border-left: 4px solid;
        }
        .stat-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .stat-card.primary { border-left-color: #667eea; }
        .stat-card.success { border-left-color: #28a745; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.danger { border-left-color: #dc3545; }
        .stat-card.info { border-left-color: #17a2b8; }
        
        .stat-title { 
            font-size: 14px; 
            color: #666; 
            margin-bottom: 10px; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-value { 
            font-size: 28px; 
            font-weight: 700; 
            color: #333; 
            margin-bottom: 5px; 
        }
        .stat-desc { 
            font-size: 12px; 
            color: #999; 
        }
        .stat-icon {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 48px;
            color: rgba(0,0,0,0.05);
        }
        
        /* Charts Row */
        .charts-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .chart-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }
        .chart-title i { 
            color: #667eea; 
            margin-right: 8px; 
        }
        
        /* Tables Row */
        .tables-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .table-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .table-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }
        .table-title i { 
            color: #667eea; 
            margin-right: 8px; 
        }
        .view-all {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .view-all:hover { 
            text-decoration: underline; 
            color: #5a67d8;
        }
        
        .table {
            width: 100%;
            margin-bottom: 0;
        }
        .table th {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            padding: 12px 10px;
            border-bottom: 2px solid #f0f0f0;
            background: #f8f9fa;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table td {
            padding: 12px 10px;
            font-size: 13px;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Badges */
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            display: inline-block;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-primary { background: #cce5ff; color: #004085; }
        
        /* Low Stock Indicators */
        .stock-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .stock-critical { background: #dc3545; }
        .stock-low { background: #ffc107; }
        .stock-medium { background: #17a2b8; }
        .stock-good { background: #28a745; }
        
        /* Alert */
        .alert-lowstock {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .alert-lowstock i { 
            font-size: 24px; 
            color: #856404;
        }
        .alert-lowstock .alert-link {
            color: #533f03;
            font-weight: 600;
            text-decoration: underline;
            margin-left: 10px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            color: #666;
            font-size: 13px;
            border-top: 1px solid #e0e0e0;
        }
        
        /* Loading */
        .loading { 
            text-align: center; 
            padding: 40px; 
            color: #666; 
        }
        .loading i { 
            font-size: 40px; 
            color: #667eea; 
            margin-bottom: 15px; 
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .charts-row {
                grid-template-columns: 1fr;
            }
            .tables-row {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .sidebar-header h3, .sidebar-header p, .nav-link span {
                display: none;
            }
            .nav-link i {
                margin-right: 0;
                font-size: 20px;
            }
            .main-content {
                margin-left: 70px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>POS</h3>
                <p>Magazin</p>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="/new-pos/dashboard" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/pos" class="nav-link">
                        <i class="fas fa-shopping-cart"></i> <span>POS (Savdo)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/products" class="nav-link">
                        <i class="fas fa-box"></i> <span>Mahsulotlar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/categories" class="nav-link">
                        <i class="fas fa-tags"></i> <span>Kategoriyalar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/customers" class="nav-link">
                        <i class="fas fa-users"></i> <span>Mijozlar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/debt" class="nav-link">
                        <i class="fas fa-credit-card"></i> <span>Qarzdorlar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/reports" class="nav-link">
                        <i class="fas fa-chart-bar"></i> <span>Hisobotlar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/settings" class="nav-link">
                        <i class="fas fa-cog"></i> <span>Sozlamalar</span>
                    </a>
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
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="page-title">
                    <h4>Dashboard</h4>
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
            
            <!-- Low Stock Alert -->
            <?php if (isset($lowStockCount) && $lowStockCount > 0): ?>
            <div class="alert-lowstock">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Diqqat!</strong> <?= $lowStockCount ?> ta mahsulot minimal miqdordan kam qolgan.
                    <a href="/new-pos/products?filter=lowstock" class="alert-link">Ko'rish →</a>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <!-- Bugungi savdo -->
                <div class="stat-card primary">
                    <div class="stat-title">Bugungi savdo</div>
                    <div class="stat-value"><?= number_format($stats['today']['bugungi_tushum'] ?? 0, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-desc">
                        <i class="fas fa-receipt"></i> <?= $stats['today']['bugungi_savdolar'] ?? 0 ?> ta savdo
                        <?php if (($stats['today']['faol_kassirlar'] ?? 0) > 0): ?>
                        | <i class="fas fa-user"></i> <?= $stats['today']['faol_kassirlar'] ?> ta kassir
                        <?php endif; ?>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                
                <!-- Oylik savdo -->
                <div class="stat-card success">
                    <div class="stat-title">Oylik savdo</div>
                    <div class="stat-value"><?= number_format($stats['monthly']['oylik_tushum'] ?? 0, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-desc">
                        <i class="fas fa-receipt"></i> <?= $stats['monthly']['oylik_savdolar'] ?? 0 ?> ta savdo
                        | <i class="fas fa-chart-line"></i> O'rtacha: <?= number_format($stats['monthly']['ortacha_chek'] ?? 0, 0, ',', ' ') ?> so'm
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                
                <!-- Jami qarz -->
                <div class="stat-card warning">
                    <div class="stat-title">Jami qarz</div>
                    <div class="stat-value"><?= number_format($stats['total']['jami_qarz'] ?? 0, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-desc">
                        <i class="fas fa-users"></i> <?= count($topDebtors ?? []) ?> ta qarzdor
                        | <i class="fas fa-percent"></i> <?= $stats['total']['jami_tushum'] > 0 ? round(($stats['total']['jami_qarz'] / $stats['total']['jami_tushum']) * 100, 1) : 0 ?>% qarz
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                </div>
                
                <!-- Kam qolgan mahsulotlar -->
                <div class="stat-card danger">
                    <div class="stat-title">Kam qolgan mahsulotlar</div>
                    <div class="stat-value"><?= $lowStockCount ?? 0 ?> ta</div>
                    <div class="stat-desc">
                        <i class="fas fa-box"></i> Jami mahsulotlar: <?= $stats['total']['jami_mahsulotlar'] ?? 0 ?>
                        | <i class="fas fa-exclamation-triangle"></i> <?= $lowStockCount > 0 ? round(($lowStockCount / ($stats['total']['jami_mahsulotlar'] ?? 1)) * 100, 1) : 0 ?>%
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            
            <!-- Charts Row -->
            <div class="charts-row">
                <!-- Oxirgi 7 kunlik savdo grafigi -->
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <i class="fas fa-chart-line"></i> Oxirgi 7 kunlik savdo
                        </div>
                        <span class="badge badge-info">so'm</span>
                    </div>
                    <canvas id="salesChart" style="height: 250px; width: 100%;"></canvas>
                </div>
                
                <!-- Top kategoriyalar -->
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <i class="fas fa-chart-pie"></i> Kategoriyalar bo'yicha
                        </div>
                        <span class="badge badge-info">so'm</span>
                    </div>
                    <canvas id="categoryChart" style="height: 250px; width: 100%;"></canvas>
                </div>
            </div>
            
            <!-- Tables Row 1 -->
            <div class="tables-row">
                <!-- Kam qolgan mahsulotlar -->
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title">
                            <i class="fas fa-exclamation-triangle text-warning"></i> Kam qolgan mahsulotlar
                        </div>
                        <a href="/new-pos/products" class="view-all">Barchasini ko'rish <i class="fas fa-arrow-right"></i></a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mahsulot</th>
                                    <th>Kategoriya</th>
                                    <th>Qoldiq</th>
                                    <th>Minimal</th>
                                    <th>Holat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lowStockProducts)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <br>Barcha mahsulotlar yetarli miqdorda
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($lowStockProducts as $product): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($product['nomi']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= $product['shtrix_kod'] ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($product['kategoriya_nomi'] ?? '-') ?></td>
                                        <td>
                                            <span class="fw-bold <?= $product['holat_darajasi'] == 'danger' ? 'text-danger' : ($product['holat_darajasi'] == 'warning' ? 'text-warning' : 'text-info') ?>">
                                                <?= $product['miqdor'] ?> <?= $product['birlik'] ?>
                                            </span>
                                        </td>
                                        <td><?= $product['minimal_miqdor'] ?> <?= $product['birlik'] ?></td>
                                        <td>
                                            <?php if ($product['miqdor'] == 0): ?>
                                                <span class="badge badge-danger">Tugagan</span>
                                            <?php elseif ($product['miqdor'] <= $product['minimal_miqdor']/2): ?>
                                                <span class="badge badge-warning">Juda kam</span>
                                            <?php else: ?>
                                                <span class="badge badge-info">Kam</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Top mahsulotlar -->
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title">
                            <i class="fas fa-star text-warning"></i> Top mahsulotlar (30 kun)
                        </div>
                        <a href="/new-pos/reports/top-products" class="view-all">Barchasini ko'rish <i class="fas fa-arrow-right"></i></a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mahsulot</th>
                                    <th>Sotilgan</th>
                                    <th>Summa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($topProducts)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                            <br>Ma'lumot yo'q
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($topProducts as $index => $product): ?>
                                    <tr>
                                        <td><strong>#<?= $index + 1 ?></strong></td>
                                        <td>
                                            <strong><?= htmlspecialchars($product['nomi']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= $product['kategoriya_nomi'] ?? '-' ?></small>
                                        </td>
                                        <td><?= $product['jami_sotilgan'] ?> <?= $product['birlik'] ?></td>
                                        <td><strong><?= number_format($product['jami_tushum'], 0, ',', ' ') ?> so'm</strong></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Tables Row 2 -->
            <div class="tables-row">
                <!-- Oxirgi savdolar -->
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title">
                            <i class="fas fa-history"></i> Oxirgi savdolar
                        </div>
                        <a href="/new-pos/reports" class="view-all">Barchasini ko'rish <i class="fas fa-arrow-right"></i></a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Chek №</th>
                                    <th>Vaqt</th>
                                    <th>Kassir</th>
                                    <th>Mijoz</th>
                                    <th>Summa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentSales)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-receipt fa-2x text-muted mb-2"></i>
                                            <br>Savdolar yo'q
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentSales as $sale): ?>
                                    <tr>
                                        <td><strong><?= $sale['chek_raqami'] ?></strong></td>
                                        <td><?= date('d.m H:i', strtotime($sale['sotilgan_vaqt'])) ?></td>
                                        <td><?= htmlspecialchars($sale['kassir_fio'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($sale['mijoz_fio'] ?? 'Anonim') ?></td>
                                        <td><strong><?= number_format($sale['yakuniy_summa'], 0, ',', ' ') ?> so'm</strong></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Eng katta qarzdorlar -->
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title">
                            <i class="fas fa-credit-card text-danger"></i> Eng katta qarzdorlar
                        </div>
                        <a href="/new-pos/debt" class="view-all">Barchasini ko'rish <i class="fas fa-arrow-right"></i></a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mijoz</th>
                                    <th>Telefon</th>
                                    <th>Qarz</th>
                                    <th>Savdolar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($topDebtors)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-smile fa-2x text-success mb-2"></i>
                                            <br>Qarzdorlar yo'q
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($topDebtors as $debtor): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($debtor['fio']) ?></strong>
                                        </td>
                                        <td><?= $debtor['telefon'] ?? '-' ?></td>
                                        <td class="text-danger fw-bold"><?= number_format($debtor['jami_qarz'], 0, ',', ' ') ?> so'm</td>
                                        <td><?= $debtor['qarzli_savdolar'] ?> ta</td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Kassirlar reytingi -->
            <?php if (!empty($cashierRanking)): ?>
            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-trophy text-warning"></i> Kassirlar reytingi (30 kun)
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kassir</th>
                                <th>Savdolar soni</th>
                                <th>Jami savdo</th>
                                <th>O'rtacha chek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cashierRanking as $index => $cashier): ?>
                            <tr>
                                <td>
                                    <?php if ($index == 0): ?>
                                        <span class="badge badge-warning"><i class="fas fa-crown"></i> 1</span>
                                    <?php elseif ($index == 1): ?>
                                        <span class="badge badge-secondary">2</span>
                                    <?php elseif ($index == 2): ?>
                                        <span class="badge badge-info">3</span>
                                    <?php else: ?>
                                        <?= $index + 1 ?>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= htmlspecialchars($cashier['fio']) ?></strong></td>
                                <td><?= $cashier['savdolar_soni'] ?> ta</td>
                                <td><?= number_format($cashier['jami_savdo'], 0, ',', ' ') ?> so'm</td>
                                <td><?= number_format($cashier['ortacha_chek'], 0, ',', ' ') ?> so'm</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Footer -->
            <div class="footer">
                <p>© <?= date('Y') ?> POS Magazin. Barcha huquqlar himoyalangan.</p>
                <p class="small text-muted">Oxirgi yangilanish: <?= date('d.m.Y H:i:s') ?></p>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Oxirgi 7 kunlik savdo grafigi
        const ctx1 = document.getElementById('salesChart').getContext('2d');
        
        // PHP dan ma'lumotlarni olish
        const dailyStats = <?= json_encode($dailyStats ?? []) ?>;
        
        // Ma'lumotlarni tayyorlash
        const labels = [];
        const salesData = [];
        
        // Oxirgi 7 kunni to'ldirish
        const today = new Date();
        for (let i = 6; i >= 0; i--) {
            const date = new Date(today);
            date.setDate(today.getDate() - i);
            const dateStr = date.toISOString().split('T')[0];
            const dayStr = date.getDate().toString().padStart(2, '0') + '.' + (date.getMonth() + 1).toString().padStart(2, '0');
            
            labels.push(dayStr);
            
            // Statistikadan ma'lumotni topish
            const stat = dailyStats.find(s => s.sana === dateStr);
            salesData.push(stat ? stat.jami_tushum : 0);
        }
        
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Savdo summasi',
                    data: salesData,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw.toLocaleString() + ' so\'m';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' so\'m';
                            }
                        }
                    }
                }
            }
        });
        
        // Kategoriyalar grafigi
        const ctx2 = document.getElementById('categoryChart').getContext('2d');
        
        // PHP dan kategoriya ma'lumotlarini olish
        const categories = <?= json_encode($stats['topCategories'] ?? []) ?>;
        
        const categoryLabels = categories.map(c => c.nomi);
        const categoryData = categories.map(c => c.jami_summa || 0);
        
        // Agar ma'lumot bo'lmasa, placeholder ko'rsatish
        if (categoryLabels.length === 0) {
            categoryLabels.push('Ma\'lumot yo\'q');
            categoryData.push(1);
        }
        
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: [
                        '#667eea', '#28a745', '#ffc107', '#dc3545', '#17a2b8', 
                        '#6f42c1', '#fd7e14', '#20c997', '#e83e8c', '#6c757d'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                                                          font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value.toLocaleString()} so'm (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Auto refresh stats har 5 daqiqada (300000 ms)
        setInterval(function() {
            refreshStats();
        }, 300000);
        
        // Statistikani yangilash funksiyasi
        function refreshStats() {
            fetch('/new-pos/dashboard/refresh-stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Statistikani yangilash
                        location.reload(); // Eng oddiy usul
                    }
                })
                .catch(error => console.error('Error:', error));
        }
        
        // Tooltip initializatsiyasi
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Sahifa yuklanganda animatsiya
        document.addEventListener('DOMContentLoaded', function() {
            // Stat kartochkalariga animatsiya
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
            
            // Kam qolgan mahsulotlar uchun rangli indikator
            document.querySelectorAll('.stock-indicator').forEach(indicator => {
                const stock = indicator.dataset.stock;
                const min = indicator.dataset.min;
                if (stock <= 0) {
                    indicator.classList.add('stock-critical');
                } else if (stock <= min / 2) {
                    indicator.classList.add('stock-low');
                } else if (stock <= min) {
                    indicator.classList.add('stock-medium');
                } else {
                    indicator.classList.add('stock-good');
                }
            });
        });
        
        // Qisqa vaqtli xabarlar
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
                               