<!-- Page Title -->
<?php $title = 'Oylik hisobot - ' . $monthName . ' ' . $year; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
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
        flex-wrap: wrap;
    }
    
    .month-selector select, 
    .month-selector input {
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
        transition: all 0.3s;
    }
    
    .month-selector button:hover {
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
    
    .btn-success, .btn-danger, .btn-info {
        padding: 8px 20px;
        border-radius: 8px;
        color: white;
        border: none;
        transition: all 0.3s;
    }
    
    .btn-success { background: #28a745; }
    .btn-danger { background: #dc3545; }
    .btn-info { background: #17a2b8; }
    
    .btn-success:hover, .btn-danger:hover, .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .table th {
        background: #f8f9fa;
    }
    
    .text-danger {
        color: #dc3545;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .month-selector {
            flex-direction: column;
            align-items: stretch;
        }
        
        .month-selector select,
        .month-selector input,
        .month-selector button,
        .month-selector a {
            width: 100%;
        }
        
        .d-flex.gap-2 {
            flex-direction: column;
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
                borderRadius: 5,
                barPercentage: 0.7
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
    
    function exportExcel() {
        alert('Excel export hozircha tayyor emas');
    }
    
    function exportPdf() {
        alert('PDF export hozircha tayyor emas');
    }
    
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
<!-- Month Selector -->
<div class="month-selector">
    <form method="GET" class="d-flex gap-2 flex-wrap">
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
        <small class="text-muted"><?= $totalDays ?> kun</small>
    </div>
    <div class="stat-card success">
        <div class="stat-value"><?= number_format($avgDaily, 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">O'rtacha kunlik</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-value"><?= $totalChecks ?></div>
        <div class="stat-label">Jami cheklar</div>
        <small class="text-muted">O'rtacha: <?= $totalDays > 0 ? round($totalChecks / $totalDays, 1) : 0 ?> ta/kun</small>
    </div>
</div>

<!-- Chart -->
<div class="chart-container">
    <div class="card-title">
        <i class="fas fa-chart-line"></i> Kunlik savdo dinamikasi
    </div>
    <div style="height: 300px;">
        <canvas id="dailyChart"></canvas>
    </div>
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
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu oyda ma'lumot yo'q</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report as $day): ?>
                    <tr>
                        <td><strong><?= $day['kun'] ?></strong></td>
                        <td><?= $year ?>-<?= str_pad($month, 2, '0', STR_PAD_LEFT) ?>-<?= str_pad($day['kun'], 2, '0', STR_PAD_LEFT) ?></td>
                        <td><?= $day['savdolar_soni'] ?></td>
                        <td><strong><?= number_format($day['kunlik_savdo'], 0, ',', ' ') ?> so'm</strong></td>
                        <td><?= number_format($day['kunlik_chegirma'], 0, ',', ' ') ?> so'm</td>
                        <td class="<?= $day['kunlik_qarz'] > 0 ? 'text-danger fw-bold' : '' ?>">
                            <?= number_format($day['kunlik_qarz'], 0, ',', ' ') ?> so'm
                        </td>
                        <td>
                            <?= $day['savdolar_soni'] > 0 ? number_format($day['kunlik_savdo'] / $day['savdolar_soni'], 0, ',', ' ') : 0 ?> so'm
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($report)): ?>
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
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- Export Buttons -->
<div class="d-flex gap-2 justify-content-end">
    <button class="btn btn-success" onclick="exportExcel()" title="Excel formatida yuklash" data-bs-toggle="tooltip">
        <i class="fas fa-file-excel"></i> Excel
    </button>
    <button class="btn btn-danger" onclick="exportPdf()" title="PDF formatida yuklash" data-bs-toggle="tooltip">
        <i class="fas fa-file-pdf"></i> PDF
    </button>
    <button class="btn btn-info" onclick="window.print()" title="Chop etish" data-bs-toggle="tooltip">
        <i class="fas fa-print"></i> Print
    </button>
</div>