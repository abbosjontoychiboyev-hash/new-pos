<!-- Page Title -->
<?php $title = 'Kunlik hisobot - ' . date('d.m.Y', strtotime($date)); ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid;
        text-align: center;
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
        margin-bottom: 5px;
    }

    .stat-label {
        color: #666;
        font-size: 12px;
        font-weight: 500;
    }

    .card-section {
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

    .filter-section {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .filter-row {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-row select,
    .filter-row input {
        padding: 8px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        min-width: 150px;
    }

    .btn-primary, .btn-success, .btn-danger, .btn-info {
        padding: 8px 20px;
        border-radius: 8px;
        color: white;
        border: none;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-primary { background: #667eea; }
    .btn-success { background: #28a745; }
    .btn-danger { background: #dc3545; }
    .btn-info { background: #17a2b8; }

    .btn-primary:hover, .btn-success:hover, .btn-danger:hover, .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .table th {
        background: #f8f9fa;
        font-weight: 600;
        border-top: none;
    }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .cash-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
    }

    .cash-summary h4 {
        margin-bottom: 20px;
        font-weight: 600;
    }

    .cash-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    .cash-row:last-child {
        border-bottom: none;
        font-size: 18px;
        font-weight: 700;
        padding-top: 15px;
        border-top: 2px solid rgba(255,255,255,0.3);
    }

    .cash-label {
        font-weight: 500;
    }

    .cash-value {
        font-weight: 600;
    }

    .text-positive {
        color: #28a745;
    }

    .text-negative {
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-row select,
        .filter-row input,
        .filter-row button,
        .filter-row a {
            width: 100%;
        }

        .cash-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Page Content -->
<!-- Filter Section -->
<div class="filter-section">
    <form method="GET" class="filter-row">
        <div>
            <label for="date" class="form-label fw-bold">Sana:</label>
            <input type="date" id="date" name="date" value="<?= $date ?>" class="form-control" required>
        </div>

        <?php if (!empty($cashiers)): ?>
        <div>
            <label for="cashier_id" class="form-label fw-bold">Kassir:</label>
            <select id="cashier_id" name="cashier_id" class="form-control">
                <option value="">Barcha kassirlar</option>
                <?php foreach ($cashiers as $cashier): ?>
                    <option value="<?= $cashier['id'] ?>" <?= ($filters['cashier_id'] ?? '') == $cashier['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cashier['fio']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <?php if (!empty($dealers)): ?>
        <div>
            <label for="dealer_id" class="form-label fw-bold">Diller:</label>
            <select id="dealer_id" name="dealer_id" class="form-control">
                <option value="">Barcha dillerlar</option>
                <?php foreach ($dealers as $dealer): ?>
                    <option value="<?= $dealer['id'] ?>" <?= ($filters['dealer_id'] ?? '') == $dealer['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dealer['nomi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Ko'rish
        </button>

        <a href="/new-pos/reports" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </form>
</div>

<!-- Summary Cards -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-value"><?= number_format($report['sales']['gross_sales'], 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Gross Sales</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-value"><?= number_format($report['returns']['total_returns'], 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Returns</div>
    </div>
    <div class="stat-card success">
        <div class="stat-value"><?= number_format($report['sales']['gross_sales'] - $report['returns']['total_returns'], 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Net Sales</div>
    </div>
    <div class="stat-card info">
        <div class="stat-value"><?= number_format($report['sales']['cash_sales'], 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Cash Sales</div>
    </div>
    <div class="stat-card primary">
        <div class="stat-value"><?= number_format($report['sales']['card_sales'], 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Card Sales</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-value"><?= number_format($report['debt_collections']['total_collections'], 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Debt Collections</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-value"><?= number_format($report['dealer_payments']['total_payments'], 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Dealer Payments</div>
    </div>
    <div class="stat-card success">
        <div class="stat-value"><?= number_format($report['cash']['expected_cash'], 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Expected Cash</div>
    </div>
</div>

<!-- Cash Summary -->
<div class="cash-summary">
    <h4><i class="fas fa-calculator"></i> Kassa hisob-kitobi</h4>
    <div class="cash-row">
        <span class="cash-label">Boshlang'ich kassa:</span>
        <span class="cash-value"><?= number_format($report['cash']['opening_cash'], 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Naqd savdo:</span>
        <span class="cash-value">+<?= number_format($report['cash']['cash_sales'], 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Qarz to'lovlari (naqd):</span>
        <span class="cash-value">+<?= number_format($report['cash']['cash_debt_collections'], 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Diller to'lovlari (naqd):</span>
        <span class="cash-value">-<?= number_format($report['cash']['cash_dealer_payments'], 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Qaytarishlar (naqd):</span>
        <span class="cash-value">-<?= number_format($report['cash']['cash_refunds'], 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Kutilgan kassa:</span>
        <span class="cash-value <?= $report['cash']['expected_cash'] >= 0 ? 'text-positive' : 'text-negative' ?>">
            <?= number_format($report['cash']['expected_cash'], 0, ',', ' ') ?> so'm
        </span>
    </div>
    <?php if ($report['cash']['actual_cash'] !== null): ?>
    <div class="cash-row">
        <span class="cash-label">Haqiqiy kassa:</span>
        <span class="cash-value"><?= number_format($report['cash']['actual_cash'], 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Farq (<?= $report['cash']['difference'] >= 0 ? 'ortiqcha' : 'kamomad' ?>):</span>
        <span class="cash-value <?= $report['cash']['difference'] >= 0 ? 'text-positive' : 'text-negative' ?>">
            <?= number_format(abs($report['cash']['difference']), 0, ',', ' ') ?> so'm
        </span>
    </div>
    <?php endif; ?>
</div>

<!-- Sales Table -->
<div class="card-section">
    <div class="card-title">
        <i class="fas fa-shopping-cart"></i> Savdolar (<?= count($report['sales']) > 0 ? $report['sales']['total_sales'] : 0 ?> ta)
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Vaqt</th>
                    <th>Chek raqami</th>
                    <th>Kassir</th>
                    <th>Mijoz</th>
                    <th>To'lov usuli</th>
                    <th class="text-end">Summa</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report['sales']) || $report['sales']['total_sales'] == 0): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu kunda savdo bo'lmagan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <!-- Bu joyda savdolar ro'yxatini ko'rsatish kerak, lekin modeldan qaytgan ma'lumotlarda sales array ichida sales detali yo'q -->
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <p class="text-muted">Savdolar tafsilotlari bu yerda ko'rsatiladi</p>
                            <small>Jami savdolar soni: <?= $report['sales']['total_sales'] ?></small>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Dealer Payments Table -->
<div class="card-section">
    <div class="card-title">
        <i class="fas fa-hand-holding-usd"></i> Diller to'lovlari (<?= $report['dealer_payments']['payment_count'] ?> ta)
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Diller</th>
                    <th>Telefon</th>
                    <th>Sana</th>
                    <th>To'lov usuli</th>
                    <th class="text-end">Summa</th>
                    <th>Izoh</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report['dealer_payments']['payments'])): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-hand-holding-usd fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu kunda diller to'lovlari bo'lmagan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report['dealer_payments']['payments'] as $payment): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($payment['dealer_name']) ?></strong></td>
                        <td><?= htmlspecialchars($payment['dealer_phone']) ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($payment['sana'])) ?></td>
                        <td>
                            <span class="badge bg-<?= $payment['payment_method'] == 'NAQD' ? 'success' : 'primary' ?>">
                                <?= $payment['payment_method'] ?>
                            </span>
                        </td>
                        <td class="text-end"><strong><?= number_format($payment['summa'], 0, ',', ' ') ?> so'm</strong></td>
                        <td><?= htmlspecialchars($payment['izoh'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($report['dealer_payments']['payments'])): ?>
            <tfoot class="table-dark">
                <tr>
                    <th colspan="4">Jami:</th>
                    <th class="text-end"><?= number_format($report['dealer_payments']['total_payments'], 0, ',', ' ') ?> so'm</th>
                    <th></th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- Debt Collections Table -->
<div class="card-section">
    <div class="card-title">
        <i class="fas fa-money-bill-wave"></i> Qarz to'lovlari (<?= $report['debt_collections']['collection_count'] ?> ta)
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Mijoz</th>
                    <th>Telefon</th>
                    <th>Sana</th>
                    <th>To'lov usuli</th>
                    <th class="text-end">Summa</th>
                    <th>Chek</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report['debt_collections']['collections'])): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu kunda qarz to'lovlari bo'lmagan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report['debt_collections']['collections'] as $collection): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($collection['customer_name']) ?></strong></td>
                        <td><?= htmlspecialchars($collection['customer_phone']) ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($collection['tolov_vaqt'])) ?></td>
                        <td>
                            <span class="badge bg-<?= $collection['payment_method'] == 'NAQD' ? 'success' : 'primary' ?>">
                                <?= $collection['payment_method'] ?>
                            </span>
                        </td>
                        <td class="text-end"><strong><?= number_format($collection['summa'], 0, ',', ' ') ?> so'm</strong></td>
                        <td><?= htmlspecialchars($collection['sale_receipt'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($report['debt_collections']['collections'])): ?>
            <tfoot class="table-dark">
                <tr>
                    <th colspan="5">Jami:</th>
                    <th class="text-end"><?= number_format($report['debt_collections']['total_collections'], 0, ',', ' ') ?> so'm</th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- Returns Table -->
<div class="card-section">
    <div class="card-title">
        <i class="fas fa-undo"></i> Qaytarishlar (<?= $report['returns']['return_count'] ?> ta)
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Sana</th>
                    <th>Chek</th>
                    <th>Mahsulot</th>
                    <th>Miqdor</th>
                    <th class="text-end">Summa</th>
                    <th>Sabab</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report['returns']['returns'])): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-undo fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu kunda qaytarishlar bo'lmagan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report['returns']['returns'] as $return): ?>
                    <tr>
                        <td><?= date('d.m.Y H:i', strtotime($return['qaytarilgan_vaqt'])) ?></td>
                        <td><strong><?= htmlspecialchars($return['chek_raqami']) ?></strong></td>
                        <td><?= htmlspecialchars($return['product_name']) ?></td>
                        <td><?= $return['miqdor'] ?></td>
                        <td class="text-end"><strong><?= number_format($return['summa'], 0, ',', ' ') ?> so'm</strong></td>
                        <td><?= htmlspecialchars($return['sabab'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($report['returns']['returns'])): ?>
            <tfoot class="table-dark">
                <tr>
                    <th colspan="4">Jami:</th>
                    <th class="text-end"><?= number_format($report['returns']['total_returns'], 0, ',', ' ') ?> so'm</th>
                    <th></th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- Export Buttons -->
<div class="d-flex gap-2 justify-content-end">
    <a href="/new-pos/reports/export-excel/daily?start_date=<?= $date ?>&end_date=<?= $date ?>&cashier_id=<?= $filters['cashier_id'] ?? '' ?>&dealer_id=<?= $filters['dealer_id'] ?? '' ?>" class="btn btn-success" title="Excel formatida yuklash">
        <i class="fas fa-file-excel"></i> Excel
    </a>
    <a href="/new-pos/reports/export-pdf/daily?date=<?= $date ?>&cashier_id=<?= $filters['cashier_id'] ?? '' ?>&dealer_id=<?= $filters['dealer_id'] ?? '' ?>" class="btn btn-danger" title="PDF formatida yuklash">
        <i class="fas fa-file-pdf"></i> PDF
    </a>
    <a href="/new-pos/reports/print/daily?date=<?= $date ?>&cashier_id=<?= $filters['cashier_id'] ?? '' ?>&dealer_id=<?= $filters['dealer_id'] ?? '' ?>" class="btn btn-info" title="Chop etish" target="_blank">
        <i class="fas fa-print"></i> Print
    </a>
</div>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Tooltips initialization
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[title]');
        if (tooltips.length > 0) {
            tooltips.forEach(tooltip => {
                tooltip.setAttribute('data-bs-toggle', 'tooltip');
                tooltip.setAttribute('data-bs-placement', 'top');
            });
            // Initialize Bootstrap tooltips if available
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
            }
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>