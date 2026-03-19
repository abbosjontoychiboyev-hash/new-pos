<!-- Page Title -->
<?php
$start_date = $start_date ?? date('Y-m-01');
$title = 'Oylik hisobot - ' . date('m.Y', strtotime($start_date));
?>

<!-- Extra CSS -->
<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .pos-container {
        display: grid;
        grid-template-columns: minmax(0, 1fr) clamp(21.25rem, 24vw, 26.25rem); /* 340px → 21.25rem, 420px → 26.25rem */
        gap: 1rem; /* 16px */
        align-items: start;
        min-height: calc(100vh - 9.375rem); /* 150px */
        max-width: 87.5rem; /* 1400px – katta ekranlarda cheklash */
        margin-left: auto;
        margin-right: auto;
    }

    .products-section,
    .cart-section {
        background: #fff;
        border-radius: 0.75rem; /* 12px */
        box-shadow: 0 0.125rem 0.625rem rgba(0,0,0,0.05); /* 2px, 10px */
        min-height: 0;
    }

    /* Mahsulotlar bo‘limi */
    .products-section {
        padding: 0.875rem; /* 14px */
        display: flex;
        flex-direction: column;
        height: calc(100vh - 9.375rem); /* 150px */
        overflow: hidden;
    }

    .products-toolbar {
        flex-shrink: 0;
        margin-bottom: 0.75rem; /* 12px */
        position: sticky;
        top: 0;
        z-index: 5;
        background: #fff;
        padding-bottom: 0.5rem; /* 8px */
    }

    .search-box {
        margin-bottom: 0;
    }

    .products-scroll {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        padding-right: 0.25rem; /* 4px */
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(9.375rem, 1fr)); /* 150px */
        gap: 0.75rem; /* 12px */
        align-content: flex-start;
    }

    .product-card {
        background: #f8f9fa;
        border-radius: 0.625rem; /* 10px */
        padding: 0.75rem; /* 12px */
        cursor: pointer;
        transition: 0.2s ease;
        border: 0.125rem solid transparent; /* 2px */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-width: 8.75rem; /* 140px */
    }

    .product-card:hover {
        transform: translateY(-0.125rem); /* -2px */
        border-color: #667eea;
        box-shadow: 0 0.375rem 1.125rem rgba(102,126,234,0.12); /* 6px, 18px */
    }

    .product-card .barcode {
        font-size: 0.6875rem; /* 11px */
        color: #888;
        line-height: 1.2;
        margin-bottom: 0.375rem; /* 6px */
        word-break: break-all;
    }

    .product-card .name {
        font-size: 0.875rem; /* 14px */
        font-weight: 600;
        color: #222;
        line-height: 1.3;
        margin-bottom: 0.5rem; /* 8px */
        min-height: 2.25rem; /* 36px */
    }

    .product-card .price {
        font-size: 1.125rem; /* 18px */
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.375rem; /* 6px */
    }

    .product-card .stock {
        font-size: 0.75rem; /* 12px */
        color: #666;
    }

    .product-card .stock.low {
        color: #dc3545;
        font-weight: 600;
    }

    /* Savat bo‘limi */
    .cart-section {
        padding: 1rem; /* 16px */
        display: flex;
        flex-direction: column;
        height: calc(100vh - 9.375rem); /* 150px */
        position: sticky;
        top: 0.625rem; /* 10px */
        overflow: hidden;
    }

    .cart-header {
        font-size: 1.25rem; /* 20px */
        font-weight: 700;
        color: #333;
        padding-bottom: 0.75rem; /* 12px */
        border-bottom: 0.125rem solid #f0f0f0; /* 2px */
        margin-bottom: 0.75rem; /* 12px */
        flex-shrink: 0;
    }

    .cart-header i {
        color: #667eea;
        margin-right: 0.5rem; /* 8px */
    }

    .cart-items-wrap {
        flex: 1;
        min-height: 7.5rem; /* 120px */
        overflow-y: auto;
        margin-bottom: 0.75rem; /* 12px */
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .cart-table th {
        text-align: left;
        padding: 0.5rem 0.25rem; /* 8px 4px */
        color: #666;
        font-weight: 600;
        font-size: 0.75rem; /* 12px */
        border-bottom: 0.0625rem solid #e0e0e0; /* 1px */
    }

    .cart-table td {
        padding: 0.5rem 0.25rem; /* 8px 4px */
        border-bottom: 0.0625rem solid #f0f0f0; /* 1px */
        vertical-align: middle;
        font-size: 0.8125rem; /* 13px */
    }

    .cart-table th:nth-child(1),
    .cart-table td:nth-child(1) {
        width: 42%;
    }
    .cart-table th:nth-child(2),
    .cart-table td:nth-child(2) {
        width: 24%;
    }
    .cart-table th:nth-child(3),
    .cart-table td:nth-child(3) {
        width: 17%;
    }
    .cart-table th:nth-child(4),
    .cart-table td:nth-child(4) {
        width: 17%;
        text-align: right;
    }

    .cart-product-name {
        font-weight: 600;
        color: #333;
        display: block;
        line-height: 1.25;
    }

    .cart-product-sku {
        font-size: 0.6875rem; /* 11px */
        color: #999;
        display: block;
        margin-top: 0.125rem; /* 2px */
        word-break: break-all;
    }

    .cart-qty-input {
        width: 3.625rem; /* 58px */
        padding: 0.25rem; /* 4px */
        border: 0.0625rem solid #dcdcdc; /* 1px */
        border-radius: 0.375rem; /* 6px */
        text-align: center;
    }

    .cart-remove {
        color: #dc3545;
        cursor: pointer;
        margin-left: 0.375rem; /* 6px */
    }

    .cart-price {
        font-weight: 600;
        color: #667eea;
        font-size: 0.75rem; /* 12px */
    }

    .discount-section label {
        margin-bottom: 0.375rem; /* 6px */
    }

    .totals {
        margin: 0.75rem 0; /* 12px */
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 0.3125rem 0; /* 5px */
        font-size: 0.875rem; /* 14px */
    }

    .total-row.final {
        font-size: 1.25rem; /* 20px */
        font-weight: 700;
        color: #333;
        border-top: 0.125rem solid #e0e0e0; /* 2px */
        padding-top: 0.625rem; /* 10px */
        margin-top: 0.375rem; /* 6px */
    }

    .payment-methods {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem; /* 8px */
        margin-bottom: 0.75rem; /* 12px */
    }

    .func-btn {
        background: #f8f9fa;
        border: 0.0625rem solid #e0e0e0; /* 1px */
        border-radius: 1.25rem; /* 20px */
        padding: 0.4375rem 0.75rem; /* 7px 12px */
        font-size: 0.75rem; /* 12px */
        font-weight: 600;
        color: #555;
        cursor: pointer;
        transition: 0.2s;
        user-select: none;
    }

    .func-btn:hover {
        background: #eef1f7;
        border-color: #bfc7d8;
    }

    .func-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-color: transparent;
    }

    .func-btn i {
        margin-right: 0.3125rem; /* 5px */
    }

    .change-amount {
        font-size: 0.875rem; /* 14px */
        font-weight: 600;
        color: #198754;
    }

    .confirm-btn {
        width: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 0.75rem; /* 12px */
        padding: 0.875rem; /* 14px */
        color: #fff;
        font-size: 1.0625rem; /* 17px */
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s ease;
        margin-top: 0.5rem; /* 8px */
    }

    .confirm-btn:hover {
        transform: translateY(-0.0625rem); /* -1px */
        box-shadow: 0 0.3125rem 0.9375rem rgba(102,126,234,0.25); /* 5px 15px */
    }

    /* Responsive media querylar */
    @media (max-width: 100rem) { /* 1600px */
        .product-card {
            min-width: 8.75rem; /* 140px */
        }
    }

    @media (max-width: 85.375rem) { /* 1366px */
        .pos-container {
            grid-template-columns: minmax(0, 1fr) 22.5rem; /* 360px */
        }
        .product-card {
            min-width: 8.125rem; /* 130px */
        }
    }

    @media (max-width: 75rem) { /* 1200px */
        .pos-container {
            grid-template-columns: 1fr;
        }
        .products-section,
        .cart-section {
            height: auto;
            position: static;
        }
    }

    @media (max-width: 48rem) { /* 768px */
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
            gap: 0.3125rem; /* 5px */
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Page Content -->
<!-- Filter Section -->
<div class="filter-section">
    <form method="GET" class="filter-row">
        <div>
            <label for="month" class="form-label fw-bold">Oy:</label>
            <input type="month" id="month" name="month" value="<?= date('Y-m', strtotime($start_date)) ?>" class="form-control" required>
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
<?php
// Ensure we have a summary array (controller should provide it).
$summary = is_array($summary) ? $summary : [];

// Ensure we have a report array (daily rows list).
$report = is_array($report) ? $report : [];

// The monthly view uses a daily report array ($report) plus a separate summary array ($summary).
// This avoids warnings when $report is a list of days (no 'sales' / 'returns' keys).
$summarySales = $summary['sales'] ?? [];
$summaryReturns = $summary['returns'] ?? [];
$summaryCash = $summary['cash'] ?? [];
$summaryDealer = $summary['dealer_payments'] ?? [];
$summaryDebt = $summary['debt_collections'] ?? [];
?>
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-value"><?= number_format($summarySales['gross_sales'] ?? 0, 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Gross Sales</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-value"><?= number_format($summaryReturns['total_returns'] ?? 0, 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Returns</div>
    </div>
    <div class="stat-card success">
        <div class="stat-value"><?= number_format(($summarySales['gross_sales'] ?? 0) - ($summaryReturns['total_returns'] ?? 0), 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Net Sales</div>
    </div>
    <div class="stat-card info">
        <div class="stat-value"><?= number_format($summarySales['cash_sales'] ?? 0, 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Cash Sales</div>
    </div>
    <div class="stat-card primary">
        <div class="stat-value"><?= number_format($summarySales['card_sales'] ?? 0, 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Card Sales</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-value"><?= number_format($summaryDebt['total_collections'] ?? 0, 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Debt Collections</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-value"><?= number_format($summaryDealer['total_payments'] ?? 0, 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Dealer Payments</div>
    </div>
    <div class="stat-card success">
        <div class="stat-value"><?= number_format($summaryCash['expected_cash'] ?? 0, 0, ',', ' ') ?> so'm</div>
        <div class="stat-label">Expected Cash</div>
    </div>
</div>

<!-- Performance Chart Placeholder -->
<div class="performance-chart">
    <h4><i class="fas fa-chart-line"></i> Oylik tendensiya</h4>
    <div class="chart-placeholder">
        <div class="text-center">
            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
            <p>Grafik bu yerda ko'rsatiladi</p>
            <small class="text-muted">Chart.js yoki boshqa grafik kutubxona qo'shilishi mumkin</small>
        </div>
    </div>
</div>

<?php
// Cash summary data comes from the monthly report summary (not the daily row list).
$cashData = $summaryCash;
$openingCash = $cashData['opening_cash'] ?? 0;
$cashSales = $cashData['cash_sales'] ?? 0;
$cashDebt = $cashData['cash_debt_collections'] ?? 0;
$dealerPayments = $cashData['cash_dealer_payments'] ?? 0;
$cashRefunds = $cashData['cash_refunds'] ?? 0;
$expectedCash = $cashData['expected_cash'] ?? 0;
$actualCash = $cashData['actual_cash'] ?? null;
$difference = $cashData['difference'] ?? null;
?>

<!-- Cash Summary -->
<div class="cash-summary">
    <h4><i class="fas fa-calculator"></i> Kassa hisob-kitobi</h4>
    <div class="cash-row">
        <span class="cash-label">Boshlang'ich kassa:</span>
        <span class="cash-value"><?= number_format($openingCash, 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Naqd savdo:</span>
        <span class="cash-value">+<?= number_format($cashSales, 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Qarz to'lovlari (naqd):</span>
        <span class="cash-value">+<?= number_format($cashDebt, 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Diller to'lovlari (naqd):</span>
        <span class="cash-value">-<?= number_format($dealerPayments, 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Qaytarishlar (naqd):</span>
        <span class="cash-value">-<?= number_format($cashRefunds, 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Kutilgan kassa:</span>
        <span class="cash-value <?= $expectedCash >= 0 ? 'text-positive' : 'text-negative' ?>">
            <?= number_format($expectedCash, 0, ',', ' ') ?> so'm
        </span>
    </div>
    <?php if ($actualCash !== null): ?>
    <div class="cash-row">
        <span class="cash-label">Haqiqiy kassa:</span>
        <span class="cash-value"><?= number_format($actualCash, 0, ',', ' ') ?> so'm</span>
    </div>
    <div class="cash-row">
        <span class="cash-label">Farq (<?= ($difference ?? 0) >= 0 ? 'ortiqcha' : 'kamomad' ?>):</span>
        <span class="cash-value <?= ($difference ?? 0) >= 0 ? 'text-positive' : 'text-negative' ?>">
            <?= number_format(abs($difference ?? 0), 0, ',', ' ') ?> so'm
        </span>
    </div>
    <?php endif; ?>
</div>

<!-- Weekly Breakdown -->
<?php $weeklyBreakdown = $summary['weekly_breakdown'] ?? []; ?>
<div class="card-section">
    <div class="card-title">
        <i class="fas fa-calendar-week"></i> Haftalik tafsilotlar
    </div>
    <?php if (!empty($weeklyBreakdown)): ?>
        <?php foreach ($weeklyBreakdown as $week => $weekData): ?>
        <div class="weekly-breakdown">
            <h5><i class="fas fa-calendar-week"></i> <?= $week ?>. hafta</h5>
            <div class="row">
                <div class="col-md-3">
                    <strong>Savdo:</strong> <?= number_format($weekData['gross_sales'] ?? 0, 0, ',', ' ') ?> so'm
                </div>
                <div class="col-md-3">
                    <strong>Qaytarish:</strong> <?= number_format($weekData['returns'] ?? 0, 0, ',', ' ') ?> so'm
                </div>
                <div class="col-md-3">
                    <strong>Qarz to'lovlari:</strong> <?= number_format($weekData['debt_collections'] ?? 0, 0, ',', ' ') ?> so'm
                </div>
                <div class="col-md-3">
                    <strong>Diller to'lovlari:</strong> <?= number_format($weekData['dealer_payments'] ?? 0, 0, ',', ' ') ?> so'm
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-4">
            <i class="fas fa-calendar-week fa-3x text-muted mb-3"></i>
            <p class="text-muted">Haftalik tafsilotlar mavjud emas</p>
        </div>
    <?php endif; ?>
</div>

<!-- Sales Table -->
<div class="card-section">
    <div class="card-title">
        <i class="fas fa-shopping-cart"></i> Savdolar (<?= $summarySales['total_sales'] ?? 0 ?> ta)
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Sana</th>
                    <th>Vaqt</th>
                    <th>Chek raqami</th>
                    <th>Kassir</th>
                    <th>Mijoz</th>
                    <th>To'lov usuli</th>
                    <th class="text-end">Summa</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($summarySales) || ($summarySales['total_sales'] ?? 0) == 0): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu oyda savdo bo'lmagan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p class="text-muted">Savdolar tafsilotlari bu yerda ko'rsatiladi</p>
                            <small>Jami savdolar soni: <?= $summarySales['total_sales'] ?></small>
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
        <i class="fas fa-hand-holding-usd"></i> Diller to'lovlari (<?= $summaryDealer['payment_count'] ?? 0 ?> ta)
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
                <?php if (empty($summaryDealer['payments'])): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-hand-holding-usd fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu oyda diller to'lovlari bo'lmagan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($summaryDealer['payments'] as $payment): ?>
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
            <?php if (!empty($summaryDealer['payments'])): ?>
            <tfoot class="table-dark">
                <tr>
                    <th colspan="5">Jami:</th>
                    <th class="text-end"><?= number_format($summaryDealer['total_payments'] ?? 0, 0, ',', ' ') ?> so'm</th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- Debt Collections Table -->
<div class="card-section">
    <div class="card-title">
        <i class="fas fa-money-bill-wave"></i> Qarz to'lovlari (<?= $summaryDebt['collection_count'] ?? 0 ?> ta)
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
                <?php if (empty($summaryDebt['collections'])): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu oyda qarz to'lovlari bo'lmagan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($summaryDebt['collections'] as $collection): ?>
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
            <?php if (!empty($summaryDebt['collections'])): ?>
            <tfoot class="table-dark">
                <tr>
                    <th colspan="5">Jami:</th>
                    <th class="text-end"><?= number_format($summaryDebt['total_collections'] ?? 0, 0, ',', ' ') ?> so'm</th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- Returns Table -->
<div class="card-section">
    <div class="card-title">
        <i class="fas fa-undo"></i> Qaytarishlar (<?= $summaryReturns['return_count'] ?? 0 ?> ta)
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
                <?php if (empty($summaryReturns['returns'])): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-undo fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu oyda qaytarishlar bo'lmagan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($summaryReturns['returns'] as $return): ?>
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
            <?php if (!empty($summaryReturns['returns'])): ?>
            <tfoot class="table-dark">
                <tr>
                    <th colspan="4">Jami:</th>
                    <th class="text-end"><?= number_format($summaryReturns['total_returns'] ?? 0, 0, ',', ' ') ?> so'm</th>
                    <th></th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<!-- Export Buttons -->
<div class="d-flex gap-2 justify-content-end">
    <a href="/new-pos/reports/export-excel/monthly?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&cashier_id=<?= $filters['cashier_id'] ?? '' ?>&dealer_id=<?= $filters['dealer_id'] ?? '' ?>" class="btn btn-success" title="Excel formatida yuklash">
        <i class="fas fa-file-excel"></i> Excel
    </a>
    <a href="/new-pos/reports/export-pdf/monthly?month=<?= date('Y-m', strtotime($start_date)) ?>&cashier_id=<?= $filters['cashier_id'] ?? '' ?>&dealer_id=<?= $filters['dealer_id'] ?? '' ?>" class="btn btn-danger" title="PDF formatida yuklash">
        <i class="fas fa-file-pdf"></i> PDF
    </a>
    <a href="/new-pos/reports/print/monthly?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&cashier_id=<?= $filters['cashier_id'] ?? '' ?>&dealer_id=<?= $filters['dealer_id'] ?? '' ?>" class="btn btn-info" title="Chop etish" target="_blank">
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
                tooltips.forEach(tooltips => new bootstrap.Tooltip(tooltips));
            }
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>
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
<div class="container py-4">
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title mb-3"><i class="fas fa-chart-bar"></i> Oylik hisobot</h4>
            <form method="GET" class="row gx-3 gy-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label for="month" class="form-label">Oy</label>
                    <input type="month" id="month" name="month" value="<?= date('Y-m', strtotime($start_date)) ?>" class="form-control" required>
                </div>
                <div class="col-12 col-md-3">
                    <label for="year" class="form-label">Yil</label>
                    <input type="number" id="year" name="year" value="<?= $year ?>" class="form-control" min="2020" max="2030">
                </div>
                <?php if (!empty($cashiers)): ?>
                <div class="col-12 col-md-3">
                    <label for="cashier_id" class="form-label">Kassir</label>
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
                <div class="col-12 col-md-3">
                    <label for="dealer_id" class="form-label">Diller</label>
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
                <div class="col-12 col-md-3 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Ko'rish
                    </button>
                    <a href="/new-pos/reports" class="btn btn-outline-secondary mt-2">
                        <i class="fas fa-arrow-left"></i> Orqaga
                    </a>
                </div>
            </form>
        </div>
    </div>

<?php 
// Calculate totals
$totalSales = 0;
$totalDays = count($report);
$totalChecks = 0;
$totalDiscount = 0;
$totalDebt = 0;

foreach ($report as $day) {
    $totalSales += floatval($day['kunlik_savdo'] ?? 0);
    $totalChecks += intval($day['savdolar_soni'] ?? 0);
    $totalDiscount += floatval($day['kunlik_chegirma'] ?? 0);
    $totalDebt += floatval($day['kunlik_qarz'] ?? 0);
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
                        <td><strong><?= htmlspecialchars($day['kun'] ?? '-') ?></strong></td>
                        <td><?= $year ?>-<?= str_pad($month, 2, '0', STR_PAD_LEFT) ?>-<?= str_pad($day['kun'] ?? '-', 2, '0', STR_PAD_LEFT) ?></td>
                        <td><?= intval($day['savdolar_soni'] ?? 0) ?></td>
                        <td><strong><?= number_format(floatval($day['kunlik_savdo'] ?? 0), 0, ',', ' ') ?> so'm</strong></td>
                        <td><?= number_format(floatval($day['kunlik_chegirma'] ?? 0), 0, ',', ' ') ?> so'm</td>
                        <td class="<?= (floatval($day['kunlik_qarz'] ?? 0) > 0) ? 'text-danger fw-bold' : '' ?>">
                            <?= number_format(floatval($day['kunlik_qarz'] ?? 0), 0, ',', ' ') ?> so'm
                        </td>
                        <td>
                            <?= (intval($day['savdolar_soni'] ?? 0) > 0) ? number_format(floatval($day['kunlik_savdo'] ?? 0) / intval($day['savdolar_soni'] ?? 1), 0, ',', ' ') : 0 ?> so'm
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
</div>