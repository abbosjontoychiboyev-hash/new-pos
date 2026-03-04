<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hisobotlar - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            border-left: 4px solid;
        }
        .stat-card.primary { border-left-color: #667eea; }
        .stat-card.success { border-left-color: #28a745; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.danger { border-left-color: #dc3545; }
        .stat-card.info { border-left-color: #17a2b8; }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        
        .table-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .card-title i {
            color: #667eea;
            margin-right: 10px;
        }
        
        .table th {
            background: #f8f9fa;
        }
        
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        
        .report-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .report-actions a {
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
        }
        .btn-excel { background: #28a745; }
        .btn-pdf { background: #dc3545; }
        .btn-print { background: #17a2b8; }
        
        .date-filter {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
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
                <li class="nav-item"><a href="/new-pos/reports" class="nav-link active"><i class="fas fa-chart-bar"></i> Hisobotlar</a></li>
                <li class="nav-item"><a href="/new-pos/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Chiqish</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <h4>Hisobotlar</h4>
                <div class="user-info">
                    <span><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></span>
                    <span class="badge bg-info"><?= $_SESSION['user']['rol_nomi'] ?? 'Role' ?></span>
                </div>
            </div>
            
            <!-- Report Actions -->
            <div class="report-actions">
                <a href="/new-pos/reports/daily" class="btn btn-primary"><i class="fas fa-calendar-day"></i> Kunlik</a>
                <a href="/new-pos/reports/monthly" class="btn btn-primary"><i class="fas fa-calendar-alt"></i> Oylik</a>
                <a href="/new-pos/reports/profit" class="btn btn-success"><i class="fas fa-chart-line"></i> Foyda</a>
                <a href="/new-pos/reports/top-products" class="btn btn-info"><i class="fas fa-chart-bar"></i> Top mahsulotlar</a>
                <a href="/new-pos/reports/cashiers" class="btn btn-warning"><i class="fas fa-users"></i> Kassirlar</a>
                <a href="/new-pos/reports/debtors" class="btn btn-danger"><i class="fas fa-credit-card"></i> Qarzdorlar</a>
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
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-value"><?= number_format($dailyReport['jami_savdo'] ?? 0, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-label">Bugungi savdo</div>
                    <small><?= $dailyReport['savdolar_soni'] ?? 0 ?> ta chek</small>
                </div>
                <div class="stat-card success">
                    <div class="stat-value"><?= number_format($dailyReport['jami_tolov'] ?? 0, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-label">To'langan</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-value"><?= number_format($dailyReport['jami_qarz'] ?? 0, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-label">Qarz</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-value"><?= number_format($dailyReport['ortacha_chek'] ?? 0, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-label">O'rtacha chek</div>
                </div>
            </div>
            
            <!-- Charts -->
            <div class="chart-container">
                <div class="card-title">
                    <i class="fas fa-chart-line"></i> Oylik savdo grafigi (<?= $currentYear ?>)
                </div>
                <canvas id="monthlyChart" height="100"></canvas>
            </div>
            
            <!-- Top Products -->
            <div class="table-card">
                <div class="card-title">
                    <i class="fas fa-chart-bar"></i> Top 10 mahsulot (oxirgi 30 kun)
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mahsulot</th>
                                <th>Kategoriya</th>
                                <th>Sotilgan</th>
                                <th>Summa</th>
                                <th>Foyda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topProducts as $index => $product): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($product['nomi']) ?></strong>
                                    <br><small class="text-muted"><?= $product['shtrix_kod'] ?></small>
                                </td>
                                <td><?= $product['kategoriya'] ?? '-' ?></td>
                                <td><?= $product['jami_soni'] ?> <?= $product['birlik'] ?></td>
                                <td><?= number_format($product['jami_summa'], 0, ',', ' ') ?> so'm</td>
                                <td class="text-success"><?= number_format($product['jami_foyda'], 0, ',', ' ') ?> so'm</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Daily Sales -->
            <div class="table-card">
                <div class="card-title">
                    <i class="fas fa-receipt"></i> Bugungi savdolar (<?= date('d.m.Y', strtotime($today)) ?>)
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Chek №</th>
                                <th>Vaqt</th>
                                <th>Kassir</th>
                                <th>Mijoz</th>
                                <th>Mahsulotlar</th>
                                <th>Summa</th>
                                <th>To'lov</th>
                                <th>Holat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dailySales)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Bugun savdolar yo'q</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($dailySales as $sale): ?>
                                <tr>
                                    <td><strong><?= $sale['chek_raqami'] ?></strong></td>
                                    <td><?= date('H:i', strtotime($sale['sotilgan_vaqt'])) ?></td>
                                    <td><?= $sale['kassir_fio'] ?></td>
                                    <td><?= $sale['mijoz_fio'] ?? 'Anonim' ?></td>
                                    <td><?= $sale['mahsulotlar_soni'] ?> ta</td>
                                    <td><strong><?= number_format($sale['yakuniy_summa'], 0, ',', ' ') ?> so'm</strong></td>
                                    <td>
                                        <?php 
                                        $usul = $sale['tolov_usuli'];
                                        if ($usul == 'NAQD') echo '💵 Naqd';
                                        elseif ($usul == 'KARTA') echo '💳 Karta';
                                        elseif ($usul == 'ARALASH') echo '🔄 Aralash';
                                        else echo $usul;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $holat = $sale['tolov_holati'];
                                        if ($holat == 'TOLANGAN') echo '<span class="badge bg-success">To\'langan</span>';
                                        elseif ($holat == 'QISMAN') echo '<span class="badge bg-warning">Qisman</span>';
                                        elseif ($holat == 'NASIYA') echo '<span class="badge bg-danger">Nasiya</span>';
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Debtors -->
            <?php if (!empty($debtors)): ?>
            <div class="table-card">
                <div class="card-title">
                    <i class="fas fa-credit-card text-danger"></i> Qarzdorlar
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mijoz</th>
                                <th>Telefon</th>
                                <th>Qarz miqdori</th>
                                <th>Qarzli savdolar</th>
                                <th>Oxirgi savdo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($debtors as $debtor): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($debtor['fio']) ?></strong></td>
                                <td><?= $debtor['telefon'] ?? '-' ?></td>
                                <td class="text-danger fw-bold"><?= number_format($debtor['jami_qarz'], 0, ',', ' ') ?> so'm</td>
                                <td><?= $debtor['qarzli_savdolar'] ?> ta</td>
                                                               <td><?= date('d.m.Y', strtotime($debtor['oxirgi_savdo'])) ?></td>
                                <td>
                                    <a href="/new-pos/debt/customer/<?= $debtor['id'] ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Monthly chart data
        const monthlyData = <?= json_encode($monthlyReport) ?>;
        
        // Prepare chart data
        const days = [];
        const sales = [];
        
        monthlyData.forEach(item => {
            days.push(item.kun);
            sales.push(item.kunlik_savdo);
        });
        
        // Create chart
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: days,
                datasets: [{
                    label: 'Kunlik savdo (so\'m)',
                    data: sales,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' so\'m';
                            }
                        }
                    }
                }
            }
        });
        
        // Auto close alerts
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