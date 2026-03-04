<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foyda hisoboti - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Previous styles */
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
        .stat-small {
            font-size: 13px;
            color: #999;
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
        
        .date-filter {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .date-filter input {
            padding: 8px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }
        .date-filter button {
            padding: 8px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
        }
        
        .profit-positive { color: #28a745; font-weight: 600; }
        .profit-negative { color: #dc3545; font-weight: 600; }
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
                <h4>Foyda hisoboti</h4>
                <div class="user-info">
                    <span><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></span>
                    <span class="badge bg-info"><?= $_SESSION['user']['rol_nomi'] ?? 'Role' ?></span>
                </div>
            </div>
            
            <!-- Date Filter -->
            <div class="date-filter">
                <form method="GET" class="d-flex gap-2 flex-wrap">
                    <label>Boshlanish:</label>
                    <input type="date" name="start_date" value="<?= $startDate ?>" class="form-control" style="width: auto;">
                    <label>Tugash:</label>
                    <input type="date" name="end_date" value="<?= $endDate ?>" class="form-control" style="width: auto;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filtrlash
                    </button>
                    <a href="/new-pos/reports" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Orqaga
                    </a>
                </form>
            </div>
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-value"><?= number_format($totalSales, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-label">Jami savdo</div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-value"><?= number_format($totalCost, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-label">Tannarx</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-value"><?= number_format($totalProfit, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-label">Yalpi foyda</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-value"><?= number_format($averageProfitPercent, 1) ?>%</div>
                    <div class="stat-label">Foyda foizi</div>
                </div>
            </div>
            
            <!-- Chart -->
            <div class="chart-container">
                <div class="card-title">
                    <i class="fas fa-chart-line"></i> Foyda dinamikasi
                </div>
                <canvas id="profitChart" height="100"></canvas>
            </div>
            
            <!-- Table -->
            <div class="table-card">
                <div class="card-title">
                    <i class="fas fa-table"></i> Kunlik foyda
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Sana</th>
                                <th>Savdolar</th>
                                <th>Tushum</th>
                                <th>Tannarx</th>
                                <th>Foyda</th>
                                <th>Foyda %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($profitReport)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Bu davrda ma'lumot yo'q</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($profitReport as $day): ?>
                                <tr>
                                    <td><strong><?= date('d.m.Y', strtotime($day['sana'])) ?></strong></td>
                                    <td><?= $day['savdolar_soni'] ?></td>
                                    <td><?= number_format($day['tushum'], 0, ',', ' ') ?> so'm</td>
                                    <td><?= number_format($day['tannarx'], 0, ',', ' ') ?> so'm</td>
                                    <td class="<?= $day['yalpi_foyda'] >= 0 ? 'profit-positive' : 'profit-negative' ?>">
                                        <?= number_format($day['yalpi_foyda'], 0, ',', ' ') ?> so'm
                                    </td>
                                    <td>
                                        <?= number_format($day['foyda_foizi'], 1) ?>%
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th>Jami:</th>
                                <th></th>
                                <th><?= number_format($totalSales, 0, ',', ' ') ?> so'm</th>
                                <th><?= number_format($totalCost, 0, ',', ' ') ?> so'm</th>
                                <th><?= number_format($totalProfit, 0, ',', ' ') ?> so'm</th>
                                <th><?= number_format($averageProfitPercent, 1) ?>%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Chart data
        const dates = <?= json_encode(array_column($profitReport, 'sana')) ?>;
        const profits = <?= json_encode(array_column($profitReport, 'yalpi_foyda')) ?>;
        const revenues = <?= json_encode(array_column($profitReport, 'tushum')) ?>;
        
        // Format dates
        const labels = dates.map(date => {
            const d = new Date(date);
            return d.getDate() + '.' + (d.getMonth() + 1);
        });
        
        // Create chart
        const ctx = document.getElementById('profitChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Foyda',
                        data: profits,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Tushum',
                        data: revenues,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true,
                        hidden: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
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
    </script>
</body>
</html>