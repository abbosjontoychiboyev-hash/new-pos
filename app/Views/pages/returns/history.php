<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qaytarish tarixi - POS System</title>
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
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
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
        
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .filter-form {
            display: flex;
            gap: 15px;
            align-items: flex-end;
        }
        
        .filter-group {
            flex: 1;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }
        
        .filter-group input {
            width: 100%;
            padding: 8px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
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
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .table-title i {
            color: #667eea;
            margin-right: 10px;
        }
        
        .badge-return {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .table th {
            background: #f8f9fa;
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
                <li class="nav-item"><a href="/new-pos/returns" class="nav-link"><i class="fas fa-undo-alt"></i> Qaytarish</a></li>
                <li class="nav-item"><a href="/new-pos/returns/history" class="nav-link active"><i class="fas fa-history"></i> Qaytarish tarixi</a></li>
                <li class="nav-item"><a href="/new-pos/customers" class="nav-link"><i class="fas fa-users"></i> Mijozlar</a></li>
                <li class="nav-item"><a href="/new-pos/reports" class="nav-link"><i class="fas fa-chart-bar"></i> Hisobotlar</a></li>
                <li class="nav-item"><a href="/new-pos/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Chiqish</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="top-bar">
                <h4>Qaytarish tarixi</h4>
                <div class="user-info">
                    <span><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></span>
                </div>
            </div>
            
            <!-- Filter Form -->
            <div class="filter-card">
                <form method="GET" class="filter-form">
                    <div class="filter-group">
                        <label>Boshlanish sanasi</label>
                        <input type="date" name="start_date" value="<?= $startDate ?>">
                    </div>
                    <div class="filter-group">
                        <label>Tugash sanasi</label>
                        <input type="date" name="end_date" value="<?= $endDate ?>">
                    </div>
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filtrlash
                        </button>
                    </div>
                    <div class="filter-group">
                        <a href="/new-pos/returns/history" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Returns Table -->
            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-history"></i> Qaytarilgan savdolar
                    </div>
                    <span class="badge-return">Jami: <?= count($returns) ?> ta</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Chek raqami</th>
                                <th>Sana</th>
                                <th>Kassir</th>
                                <th>Mijoz</th>
                                <th>Umumiy summa</th>
                                <th>Holat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($returns)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-undo-alt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Bu davrda qaytarilgan savdolar yo'q</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($returns as $return): ?>
                                <tr>
                                    <td><strong><?= $return['chek_raqami'] ?></strong></td>
                                    <td><?= date('d.m.Y H:i', strtotime($return['sotilgan_vaqt'])) ?></td>
                                    <td><?= htmlspecialchars($return['kassir_fio'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($return['mijoz_fio'] ?? 'Anonim') ?></td>
                                    <td class="text-danger fw-bold"><?= number_format($return['umumiy_summa'], 0, ',', ' ') ?> so'm</td>
                                    <td><span class="badge bg-danger">Bekor qilingan</span></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>