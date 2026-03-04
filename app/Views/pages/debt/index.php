<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qarzdorlar - POS System</title>
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
        .sidebar-header h3 { font-size: 24px; font-weight: 700; margin: 0; color: white; }
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
        
        .content-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
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
            color: #495057;
            font-weight: 600;
        }
        
        .badge-debt {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-warning {
            background: #ffc107;
            color: #333;
        }
        
        .badge-success {
            background: #28a745;
            color: white;
        }
        
        .debt-amount {
            font-size: 18px;
            font-weight: 700;
            color: #dc3545;
        }
        
        .overdue-badge {
            background: #dc3545;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
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
        .stat-card.danger { border-left-color: #dc3545; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-value { font-size: 28px; font-weight: 700; }
        .stat-label { color: #666; font-size: 14px; }
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
                <li class="nav-item"><a href="/new-pos/debt" class="nav-link active"><i class="fas fa-credit-card"></i> Qarzdorlar</a></li>
                <li class="nav-item"><a href="/new-pos/reports" class="nav-link"><i class="fas fa-chart-bar"></i> Hisobotlar</a></li>
                <li class="nav-item"><a href="/new-pos/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Chiqish</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="top-bar">
                <h4>Qarzdorlar</h4>
                <div class="user-info">
                    <span><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></span>
                </div>
            </div>
            
            <?php
            // Statistikani hisoblash
            $totalDebt = 0;
            $totalDebtors = count($debtors);
            $overdueCount = count($overdue);
            $overdueAmount = 0;
            
            foreach ($debtors as $d) {
                $totalDebt += $d['jami_qarz'];
            }
            
            foreach ($overdue as $o) {
                $overdueAmount += $o['qarz_summa'];
            }
            ?>
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-value"><?= number_format($totalDebt, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-label">Jami qarz</div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-value"><?= $totalDebtors ?></div>
                    <div class="stat-label">Qarzdorlar soni</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-value"><?= number_format($overdueAmount, 0, ',', ' ') ?> so'm</div>
                    <div class="stat-label">Muddati o'tgan</div>
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
            
            <!-- Debtors List -->
            <div class="content-card">
                <div class="card-title">
                    <i class="fas fa-users"></i> Qarzdorlar ro'yxati
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mijoz</th>
                                <th>Telefon</th>
                                <th>Qarz miqdori</th>
                                <th>Qarzli savdolar</th>
                                <th>Oxirgi savdo</th>
                                <th>Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($debtors)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <p>Qarzdor mijozlar mavjud emas</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($debtors as $index => $debtor): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($debtor['fio']) ?></strong>
                                    </td>
                                    <td><?= $debtor['telefon'] ?? '-' ?></td>
                                    <td>
                                        <span class="debt-amount"><?= number_format($debtor['jami_qarz'], 0, ',', ' ') ?> so'm</span>
                                    </td>
                                    <td><?= $debtor['qarzli_savdolar'] ?> ta</td>
                                    <td>
                                        <?= date('d.m.Y', strtotime($debtor['oxirgi_savdo'])) ?>
                                        <?php 
                                        $days = (time() - strtotime($debtor['oxirgi_savdo'])) / (60*60*24);
                                        if ($days > 30): 
                                        ?>
                                            <span class="overdue-badge"><?= round($days) ?> kun</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/new-pos/debt/customer/<?= $debtor['id'] ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Qarz tarixi
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Overdue Debts -->
            <?php if (!empty($overdue)): ?>
            <div class="content-card">
                <div class="card-title">
                    <i class="fas fa-exclamation-triangle text-danger"></i> Muddati o'tgan qarzlar (30+ kun)
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mijoz</th>
                                <th>Chek raqami</th>
                                <th>Qarz miqdori</th>
                                <th>Savdo sanasi</th>
                                <th>Kechikkan kun</th>
                                <th>Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($overdue as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['fio']) ?></td>
                                <td><?= $item['chek_raqami'] ?></td>
                                <td class="text-danger fw-bold"><?= number_format($item['qarz_summa'], 0, ',', ' ') ?> so'm</td>
                                <td><?= date('d.m.Y', strtotime($item['sotilgan_vaqt'])) ?></td>
                                <td><span class="badge bg-danger"><?= $item['kechikkan_kun'] ?> kun</span></td>
                                <td>
                                    <a href="/new-pos/debt/payment/<?= $item['id'] ?>" class="btn btn-sm btn-success">
                                        <i class="fas fa-money-bill"></i> To'lov
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
</body>
</html>