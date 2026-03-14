<!-- Page Title -->
<?php $title = 'Hisobotlar'; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
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
        flex-wrap: wrap;
    }
    
    .report-actions a {
        padding: 8px 15px;
        border-radius: 8px;
        text-decoration: none;
        color: white;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .report-actions a:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .btn-excel { background: #28a745; }
    .btn-pdf { background: #dc3545; }
    .btn-print { background: #17a2b8; }
    
    .date-filter {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .report-actions {
            flex-direction: column;
        }
        
        .report-actions a {
            width: 100%;
            text-align: center;
        }
        
        .date-filter {
            flex-direction: column;
            align-items: stretch;
        }
        
        .table th, .table td {
            white-space: nowrap;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly chart data
    const monthlyData = <?= json_encode($monthlyReport ?? []) ?>;
    
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
    
    // Tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltips.length > 0) {
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<!-- Report Actions -->
<div class="report-actions">
    <a href="/new-pos/reports/daily" class="btn btn-primary"><i class="fas fa-calendar-day"></i> Kunlik</a>
    <a href="/new-pos/reports/monthly" class="btn btn-primary"><i class="fas fa-calendar-alt"></i> Oylik</a>
    <a href="/new-pos/reports/profit" class="btn btn-success"><i class="fas fa-chart-line"></i> Foyda</a>
    <a href="/new-pos/reports/top-products" class="btn btn-info"><i class="fas fa-chart-bar"></i> Top mahsulotlar</a>
    <a href="/new-pos/reports/cashiers" class="btn btn-warning"><i class="fas fa-users"></i> Kassirlar</a>
    <a href="/new-pos/reports/debtors" class="btn btn-danger"><i class="fas fa-credit-card"></i> Qarzdorlar</a>
    <a href="/new-pos/reports/dealers" class="btn btn-secondary"><i class="fas fa-truck"></i> Yetkazib beruvchilar</a>
    <a href="/new-pos/reports/shifts" class="btn btn-dark"><i class="fas fa-stopwatch"></i> Smena</a>
    <a href="/new-pos/reports/returns" class="btn btn-info"><i class="fas fa-undo-alt"></i> Qaytarishlar</a>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-value"><?= number_format($dailyReport['jami_savdo'] ?? 0, 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Bugungi savdo</div>
        <small class="text-muted"><?= $dailyReport['savdolar_soni'] ?? 0 ?> ta chek</small>
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
        <i class="fas fa-chart-line"></i> Oylik savdo grafigi (<?= $currentYear ?? date('Y') ?>)
    </div>
    <div style="height: 300px;">
        <canvas id="monthlyChart"></canvas>
    </div>
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
                <?php if (empty($topProducts)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Ma'lumot yo'q</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($topProducts as $index => $product): ?>
                    <tr>
                        <td><strong>#<?= $index + 1 ?></strong></td>
                        <td>
                            <strong><?= htmlspecialchars($product['nomi']) ?></strong>
                            <br><small class="text-muted"><?= $product['shtrix_kod'] ?></small>
                        </td>
                        <td><?= $product['kategoriya'] ?? '-' ?></td>
                        <td><?= $product['jami_soni'] ?> <?= $product['birlik'] ?></td>
                        <td><?= number_format($product['jami_summa'], 0, ',', ' ') ?> so'm</td>
                        <td class="text-success fw-bold"><?= number_format($product['jami_foyda'], 0, ',', ' ') ?> so'm</td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
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
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Bugun savdolar yo'q</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($dailySales as $sale): ?>
                    <tr>
                        <td><strong><?= $sale['chek_raqami'] ?></strong></td>
                        <td><?= date('H:i', strtotime($sale['sotilgan_vaqt'])) ?></td>
                        <td><?= htmlspecialchars($sale['kassir_fio'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($sale['mijoz_fio'] ?? 'Anonim') ?></td>
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
                            else echo $holat;
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
                        <a href="/new-pos/debt/customer/<?= $debtor['id'] ?>" class="btn btn-sm btn-info" title="Qarz tarixi" data-bs-toggle="tooltip">
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