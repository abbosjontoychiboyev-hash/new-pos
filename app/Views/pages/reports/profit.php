<!-- Page Title -->
<?php $title = 'Foyda hisoboti'; ?>

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
        transition: all 0.3s;
    }
    
    .date-filter button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
    
    .btn-secondary {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        padding: 8px 20px;
        border-radius: 8px;
        color: #666;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-secondary:hover {
        background: #e0e0e0;
    }
    
    .profit-positive { color: #28a745; font-weight: 600; }
    .profit-negative { color: #dc3545; font-weight: 600; }
    
    .table th {
        background: #f8f9fa;
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
        
        .date-filter form {
            flex-direction: column;
        }
        
        .date-filter input,
        .date-filter button,
        .date-filter a {
            width: 100%;
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
                    fill: true,
                    pointBackgroundColor: '#28a745',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                },
                {
                    label: 'Tushum',
                    data: revenues,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    hidden: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw.toLocaleString() + ' so\'m';
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
        <small class="text-muted"><?= count($profitReport) ?> kun</small>
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
    <div style="height: 300px;">
        <canvas id="profitChart"></canvas>
    </div>
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
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu davrda ma'lumot yo'q</p>
                        </td>
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
            <?php if (!empty($profitReport)): ?>
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
            <?php endif; ?>
        </table>
    </div>
</div>