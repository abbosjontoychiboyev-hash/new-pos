<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oylik hisobot - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Previous styles from index.php */
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
            grid-template-columns: repeat(3, 1fr);
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
        
        .stat-value {
            font-size: 28px;
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
        
        .month-selector {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
        }
        .month-selector select, .month-selector input {
            padding: 8px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }
        .month-selector button {
            padding: 8px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
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
                <h4>Oylik hisobot - <?= $monthName ?> <?= $year ?></h4>
                <div class="user-info">
                    <span><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></span>
                    <span class="badge bg-info"><?= $_SESSION['user']['rol_nomi'] ?? 'Role' ?></span>
                </div>
            </div>
            
            <!-- Month Selector -->
            <div class="month-selector">
                <form method="GET" class="d-flex gap-2">
                    <select name="month" class="form-control" style="width: 150px;">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <?php 
                            $monthNames = [
                                1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
                                5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
                                9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr'
                            ];
                            ?>
                            <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>>
                                <?= $monthNames[$m] ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <input type="number" name="year" value="<?= $year ?>" class="form-control" style="width: 100px;" min="2020" max="2030">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Ko'rish
                    </button>
                    <a href="/new-pos/reports" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Orqaga
                    </a>
                </form>
            </div>
            
            <?php 
            // Calculate totals
            $totalSales = 0;
            $totalDays = count($report);
            $totalChecks = 0;
            $totalDiscount = 0;
            $totalDebt = 0;
            
            foreach ($report as $day) {
                $totalSales += $day['kunlik_savdo'];
                $totalChecks += $day['savdolar_soni'];
                $totalDiscount += $day['kunlik_chegirma'];
                $totalDebt += $day['kunlik_qarz'];
            }
            
            $avgDaily = $totalDays > 0 ? $totalSales / $totalDays : 0;
            ?>
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-value"><?= number_format($totalSales, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-label">Oylik savdo</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-value"><?= number_format($avgDaily, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-label">O'rtacha kunlik</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-value"><?= $totalChecks ?></div>
                    <div class="stat-label">Jami cheklar</div>
                </div>
            </div>
            
            <!-- Chart -->
            <div class="chart-container">
                <div class="card-title">
                    <i class="fas fa-chart-line"></i> Kunlik savdo dinamikasi
                </div>
                <canvas id="dailyChart" height="100"></canvas>
            </div>
            
            <!-- Daily Table -->
            <div class="table-card">
                <div class="card-title">
                    <i class="fas fa-table"></i> Kunlik ma'lumotlar
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kun</th>
                                <th>Sana</th>
                                <th>Savdolar soni</th>
                                <th>Savdo summasi</th>
                                <th>Chegirma</th>
                                <th>Qarz</th>
                                <th>O'rtacha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($report)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Bu oyda ma'lumot yo'q</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($report as $day): ?>
                                <tr>
                                    <td><strong><?= $day['kun'] ?></strong></td>
                                    <td><?= $year ?>-<?= str_pad($month, 2, '0', STR_PAD_LEFT) ?>-<?= str_pad($day['kun'], 2, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= $day['savdolar_soni'] ?></td>
                                    <td><strong><?= number_format($day['kunlik_savdo'], 0, ',', ' ') ?> so'm</strong></td>
                                    <td><?= number_format($day['kunlik_chegirma'], 0, ',', ' ') ?> so'm</td>
                                    <td class="<?= $day['kunlik_qarz'] > 0 ? 'text-danger' : '' ?>">
                                        <?= number_format($day['kunlik_qarz'], 0, ',', ' ') ?> so'm
                                    </td>
                                    <td>
                                        <?= $day['savdolar_soni'] > 0 ? number_format($day['kunlik_savdo'] / $day['savdolar_soni'], 0, ',', ' ') : 0 ?> so'm
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th>Jami:</th>
                                <th></th>
                                <th><?= $totalChecks ?></th>
                                <th><?= number_format($totalSales, 0, ',', ' ') ?> so'm</th>
                                <th><?= number_format($totalDiscount, 0, ',', ' ') ?> so'm</th>
                                <th><?= number_format($totalDebt, 0, ',', ' ') ?> so'm</th>
                                <th><?= number_format($avgDaily, 0, ',', ' ') ?> so'm</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- Export Buttons -->
            <div class="d-flex gap-2 justify-content-end">
                <button class="btn btn-success" onclick="exportExcel()">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button class="btn btn-danger" onclick="exportPdf()">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button class="btn btn-info" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Chart data
        const days = <?= json_encode(array_column($report, 'kun')) ?>;
        const sales = <?= json_encode(array_column($report, 'kunlik_savdo')) ?>;
        
        // Create chart
        const ctx = document.getElementById('dailyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                    label: 'Kunlik savdo',
                    data: sales,
                    backgroundColor: '#667eea',
                    borderRadius: 5
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
        
        function exportExcel() {
            alert('Excel export hozircha tayyor emas');
        }
        
        function exportPdf() {
            alert('PDF export hozircha tayyor emas');
        }
    </script>
</body>
</html>