<!-- Page Title -->
<?php $title = 'Dashboard'; ?>

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
        <div class="stat-title">BUGUNGI SAVDO</div>
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
        <div class="stat-title">OYLIK SAVDO</div>
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
        <div class="stat-title">JAMI QARZ</div>
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
        <div class="stat-title">KAM QOLGAN MAHSULOTLAR</div>
        <div class="stat-value"><?= $lowStockCount ?? 0 ?> ta</div>
        <div class="stat-desc">
            <i class="fas fa-box"></i> Jami mahsulotlar: <?= $stats['total']['jami_mahsulotlar'] ?? 0 ?>
            | <i class="fas fa-exclamation-triangle"></i> <?= $lowStockCount > 0 ? round(($lowStockCount / ($stats['total']['jami_mahsulotlar'] ?? 1)) * 100, 1) : 0 ?>%
        </div>
        <div class="stat-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
    </div>
    <!-- Yetkazib beruvchilarga qarz -->
    <div class="stat-card warning">
        <div class="stat-title">YETKAZIB BERUVCHILARGA QARZ</div>
        <div class="stat-value">
            <?= number_format($stats['total']['jami_yetkazib_beruvchi_qarzi'] ?? 0, 0, ',', ' ') ?> so'm
        </div>
        <div class="stat-desc">
            <i class="fas fa-truck"></i>
            <?= $stats['total']['qarzdor_yetkazib_beruvchilar_soni'] ?? 0 ?> ta yetkazib beruvchi
        </div>
        <div class="stat-icon">
            <i class="fas fa-truck-loading"></i>
        </div>
    </div>

    <!-- Ombordagi jami tannarx -->
    <div class="stat-card primary">
        <div class="stat-title">OMBORDAGI JAMI TANNARX</div>
        <div class="stat-value">
            <?= number_format($stats['total']['jami_ombor_tannarxi'] ?? 0, 0, ',', ' ') ?> so'm
        </div>
        <div class="stat-desc">
            <i class="fas fa-boxes"></i>
            Jami miqdor: <?= number_format($stats['total']['jami_ombor_miqdori'] ?? 0, 0, ',', ' ') ?>
        </div>
        <div class="stat-icon">
            <i class="fas fa-warehouse"></i>
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
            <span class="badge bg-info">so'm</span>
        </div>
        <div style="height: 300px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
    
    <!-- Top kategoriyalar -->
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">
                <i class="fas fa-chart-pie"></i> Kategoriyalar bo'yicha
            </div>
            <span class="badge bg-info">%</span>
        </div>
        <div style="height: 300px;">
            <canvas id="categoryChart"></canvas>
        </div>
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
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
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
                                    <span class="badge bg-danger">Tugagan</span>
                                <?php elseif ($product['miqdor'] <= $product['minimal_miqdor']/2): ?>
                                    <span class="badge bg-warning">Juda kam</span>
                                <?php else: ?>
                                    <span class="badge bg-info">Kam</span>
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
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
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
                        <th>Mahsulotlar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentSales)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
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
                            <td>
                                <button type="button" 
                                        class="btn btn-sm btn-info" 
                                        onclick="viewSaleDetails(<?= $sale['id'] ?>)"
                                        title="Mahsulotlarni ko'rish">
                                    <i class="fas fa-box"></i> Mahsulotlar
                                </button>
                            </td>
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
                                <i class="fas fa-smile fa-3x text-success mb-3"></i>
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
                            <span class="badge bg-warning"><i class="fas fa-crown"></i> 1</span>
                        <?php elseif ($index == 1): ?>
                            <span class="badge bg-secondary">2</span>
                        <?php elseif ($index == 2): ?>
                            <span class="badge bg-info">3</span>
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

<!-- Mahsulot detallari modal -->
<div class="modal fade" id="saleDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-receipt"></i> Savdo mahsulotlari
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="saleDetailsBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Yuklanmoqda...</span>
                    </div>
                    <p class="mt-2">Ma'lumotlar yuklanmoqda...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Yopish</button>
            </div>
        </div>
    </div>
</div>

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
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    
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
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Oxirgi 7 kunlik savdo grafigi
    const ctx1 = document.getElementById('salesChart').getContext('2d');
    const dailyStats = <?= json_encode($dailyStats ?? []) ?>;
    
    const labels = [];
    const salesData = [];
    const today = new Date();
    
    for (let i = 6; i >= 0; i--) {
        const date = new Date(today);
        date.setDate(today.getDate() - i);
        const dateStr = date.toISOString().split('T')[0];
        const dayStr = date.getDate().toString().padStart(2, '0') + '.' + (date.getMonth() + 1).toString().padStart(2, '0');
        
        labels.push(dayStr);
        
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
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => context.raw.toLocaleString() + ' so\'m'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => value.toLocaleString() + ' so\'m'
                    }
                }
            }
        }
    });
    
    // Kategoriyalar grafigi
    const ctx2 = document.getElementById('categoryChart').getContext('2d');
    const categories = <?= json_encode($stats['topCategories'] ?? []) ?>;
    
    const categoryLabels = categories.map(c => c.nomi);
    const categoryData = categories.map(c => c.jami_summa || 0);
    
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
                backgroundColor: ['#667eea', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#fd7e14', '#20c997', '#e83e8c', '#6c757d'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { size: 11 } }
                },
                tooltip: {
                    callbacks: {
                        label: (context) => {
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
    
    // Savdo mahsulotlarini ko'rish
    function viewSaleDetails(saleId) {
        // Modalni ko'rsatish
        var myModal = new bootstrap.Modal(document.getElementById('saleDetailsModal'));
        myModal.show();
        
        // Yuklanish animatsiyasini ko'rsatish
        document.getElementById('saleDetailsBody').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Yuklanmoqda...</span>
                </div>
                <p class="mt-2">Ma'lumotlar yuklanmoqda...</p>
            </div>
        `;
        
        // AJAX so'rov yuborish
        fetch('/new-pos/api/sale-details/' + saleId)
            .then(response => response.json())
            .then(data => {
                let html = '';
                
                if (data.items && data.items.length > 0) {
                    html = `
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Mahsulot</th>
                                        <th>Shtrix kod</th>
                                        <th>Soni</th>
                                        <th>Narxi</th>
                                        <th>Summa</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    
                    data.items.forEach((item, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td><strong>${item.nomi}</strong></td>
                                <td><code>${item.shtrix_kod || '-'}</code></td>
                                <td>${item.soni} ${item.birlik || 'dona'}</td>
                                <td>${formatMoney(item.birlik_narx)}</td>
                                <td>${formatMoney(item.qator_summa)}</td>
                            </tr>
                        `;
                    });
                    
                    html += `
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="5" class="text-end">Jami:</th>
                                        <th>${formatMoney(data.total)}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    `;
                    
                    // Chek ma'lumotlari
                    html += `
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p><strong>Chek raqami:</strong> ${data.sale.chek_raqami}</p>
                                <p><strong>Sana:</strong> ${new Date(data.sale.sotilgan_vaqt).toLocaleString('uz-UZ')}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Kassir:</strong> ${data.sale.kassir_fio || '-'}</p>
                                <p><strong>Mijoz:</strong> ${data.sale.mijoz_fio || 'Anonim'}</p>
                            </div>
                        </div>
                    `;
                } else {
                    html = '<div class="alert alert-warning">Bu savdoda mahsulotlar topilmadi</div>';
                }
                
                document.getElementById('saleDetailsBody').innerHTML = html;
            })
            .catch(error => {
                console.error('Xatolik:', error);
                document.getElementById('saleDetailsBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Xatolik yuz berdi. Qaytadan urinib ko'ring.
                    </div>
                `;
            });
    }
    
    // Format money (agar mavjud bo'lmasa)
    if (typeof formatMoney !== 'function') {
        function formatMoney(amount) {
            return new Intl.NumberFormat('uz-UZ', { 
                style: 'currency', 
                currency: 'UZS',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount).replace('UZS', '').trim() + ' so\'m';
        }
    }
    
    // Auto close alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
<?php $extraJs = ob_get_clean(); ?>

<?php 
// Clear old data
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>