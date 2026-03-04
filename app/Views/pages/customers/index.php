<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijozlar - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Sidebar va boshqa stillar avvalgidek */
        .wrapper { display: flex; }
        .sidebar { width: 260px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; position: fixed; }
        .main-content { flex: 1; margin-left: 260px; padding: 20px; }
        .top-bar { background: white; border-radius: 12px; padding: 15px; margin-bottom: 20px; }
        .content-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .filter-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .search-box { display: flex; gap: 10px; flex: 1; }
        .table th { background: #f8f9fa; }
        .badge-debt { background: #dc3545; color: white; padding: 5px 10px; border-radius: 20px; font-size: 12px; }
        .badge-paid { background: #28a745; color: white; padding: 5px 10px; border-radius: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar content -->
        </div>
        
        <div class="main-content">
            <div class="top-bar">
                <h4>Mijozlar</h4>
            </div>
            
            <div class="content-card">
                <!-- Filter Section -->
                <div class="filter-section">
                    <form method="GET" class="search-box">
                        <input type="text" name="search" class="form-control" placeholder="Qidirish (ism yoki telefon)" value="<?= htmlspecialchars($search ?? '') ?>">
                        <button type="submit" class="btn btn-primary">Qidirish</button>
                    </form>
                    <a href="/new-pos/customers/create" class="btn btn-success">Yangi mijoz</a>
                </div>
                
                <!-- Flash messages -->
                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['flash']['success'] ?></div>
                    <?php unset($_SESSION['flash']['success']); ?>
                <?php endif; ?>
                
                <!-- Customers Table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>F.I.O.</th>
                            <th>Telefon</th>
                            <th>Manzil</th>
                            <th>Qarz</th>
                            <th>Amallar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td>#<?= $customer['id'] ?></td>
                            <td><?= htmlspecialchars($customer['fio']) ?></td>
                            <td><?= $customer['telefon'] ?? '-' ?></td>
                            <td><?= $customer['manzil'] ?? '-' ?></td>
                            <td>
                                <?php 
                                $debt = (new App\Models\Customer())->getDebt($customer['id']);
                                if ($debt > 0): 
                                ?>
                                    <span class="badge-debt"><?= number_format($debt, 0, ',', ' ') ?> so'm</span>
                                <?php else: ?>
                                    <span class="badge-paid">0 so'm</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/new-pos/customers/edit/<?= $customer['id'] ?>" class="btn btn-sm btn-warning">Tahrirlash</a>
                                <a href="/new-pos/customers/debt/<?= $customer['id'] ?>" class="btn btn-sm btn-info">Qarz</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>