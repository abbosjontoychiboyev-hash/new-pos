<!-- resources/views/reports/monthly.php -->
<!-- Page Title -->
<?php
$start_date = $start_date ?? date('Y-m-01');
$end_date   = date('Y-m-t', strtotime($start_date));
$title = 'Oylik hisobot - ' . $month_name . ' ' . $year;
?>

<div class="main-content">
    <!-- Top Bar (agar layoutda bo'lmasa, shu yerga qo'shiladi) -->
    <div class="top-bar">
        <div class="page-title">
            <h4><i class="fas fa-chart-bar"></i> Oylik hisobot</h4>
        </div>
        <div class="user-info">
            <div class="user-details">
                <span class="user-name">Kassir: <?= htmlspecialchars($_SESSION['user_name'] ?? 'Anonim') ?></span>
                <span class="user-role"><?= $_SESSION['role'] ?? 'kassir' ?></span>
            </div>
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" class="filter-row">
            <div>
                <label for="month" class="form-label fw-bold">Oy:</label>
                <input type="month" id="month" name="month" value="<?= sprintf('%04d-%02d', $year, $month) ?>" class="form-control" required>
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
    $summary = is_array($summary) ? $summary : [];
    $summarySales   = $summary['sales'] ?? [];
    $summaryReturns = $summary['returns'] ?? [];
    $summaryCash    = $summary['cash'] ?? [];
    $summaryDealer  = $summary['dealer_payments'] ?? [];
    $summaryDebt    = $summary['debt_collections'] ?? [];
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

    <!-- Cash Summary -->
    <?php
    $openingCash   = $summaryCash['opening_cash'] ?? 0;
    $cashSales     = $summaryCash['cash_sales'] ?? 0;
    $cashDebt      = $summaryCash['cash_debt_collections'] ?? 0;
    $dealerPayments= $summaryCash['cash_dealer_payments'] ?? 0;
    $cashRefunds   = $summaryCash['cash_refunds'] ?? 0;
    $expectedCash  = $summaryCash['expected_cash'] ?? 0;
    $actualCash    = $summaryCash['actual_cash'] ?? null;
    $difference    = $summaryCash['difference'] ?? null;
    ?>
    <div class="table-card">
        <div class="card-title">
            <i class="fas fa-calculator"></i> Kassa hisob-kitobi
        </div>
        <div class="cash-summary">
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
    </div>

    <!-- Weekly Breakdown -->
    <?php $weeklyBreakdown = $summary['weekly_breakdown'] ?? []; ?>
    <?php if (!empty($weeklyBreakdown)): ?>
    <div class="table-card">
        <div class="card-title">
            <i class="fas fa-calendar-week"></i> Haftalik tafsilotlar
        </div>
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
    </div>
    <?php endif; ?>

    <!-- Daily Chart (Kunlik savdo grafigi) -->
    <?php
    // $report - kunlik ma'lumotlar massivi (getMonthlyDailyReport)
    // Har bir elementda 'kun' va 'kunlik_savdo' bo'lishi kutiladi
    $chartLabels = [];
    $chartData = [];
    if (!empty($report)) {
        foreach ($report as $day) {
            $chartLabels[] = (string)($day['kun'] ?? '');
            $chartData[] = floatval($day['kunlik_savdo'] ?? 0);
        }
    }
    ?>
    <?php if (!empty($chartData)): ?>
    <div class="chart-card">
        <div class="card-title">
            <i class="fas fa-chart-line"></i> Kunlik savdo dinamikasi
        </div>
        <div style="height: 300px;">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>
    <?php endif; ?>

    <!-- Daily Table (Kunlik ma'lumotlar) -->
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
                        <th>O'rtacha chek</th>
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
                        <?php
                        $totalSales = 0;
                        $totalChecks = 0;
                        $totalDiscount = 0;
                        $totalDebt = 0;
                        $daysCount = count($report);
                        foreach ($report as $day) {
                            $totalSales += floatval($day['kunlik_savdo'] ?? 0);
                            $totalChecks += intval($day['savdolar_soni'] ?? 0);
                            $totalDiscount += floatval($day['kunlik_chegirma'] ?? 0);
                            $totalDebt += floatval($day['kunlik_qarz'] ?? 0);
                        }
                        $avgDaily = $daysCount > 0 ? $totalSales / $daysCount : 0;
                        ?>
                        <?php foreach ($report as $day): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($day['kun'] ?? '-') ?></strong></td>
                            <td><?= sprintf('%04d-%02d-%02d', $year, $month, $day['kun']) ?></td>
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

    <!-- Dealer Payments Table -->
    <?php if (!empty($summaryDealer['payments'])): ?>
    <div class="table-card">
        <div class="card-title">
            <i class="fas fa-hand-holding-usd"></i> Diller to'lovlari (<?= $summaryDealer['payment_count'] ?? count($summaryDealer['payments']) ?> ta)
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
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="4">Jami:</th>
                        <th class="text-end"><?= number_format($summaryDealer['total_payments'] ?? 0, 0, ',', ' ') ?> so'm</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Debt Collections Table -->
    <?php if (!empty($summaryDebt['collections'])): ?>
    <div class="table-card">
        <div class="card-title">
            <i class="fas fa-money-bill-wave"></i> Qarz to'lovlari (<?= $summaryDebt['collection_count'] ?? count($summaryDebt['collections']) ?> ta)
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
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="4">Jami:</th>
                        <th class="text-end"><?= number_format($summaryDebt['total_collections'] ?? 0, 0, ',', ' ') ?> so'm</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Returns Table -->
    <?php if (!empty($summaryReturns['returns'])): ?>
    <div class="table-card">
        <div class="card-title">
            <i class="fas fa-undo"></i> Qaytarishlar (<?= $summaryReturns['return_count'] ?? count($summaryReturns['returns']) ?> ta)
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
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="4">Jami:</th>
                        <th class="text-end"><?= number_format($summaryReturns['total_returns'] ?? 0, 0, ',', ' ') ?> so'm</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Export Buttons -->
    <div class="d-flex gap-2 justify-content-end mt-4 mb-4">
        <a href="/new-pos/reports/export-excel/monthly?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&cashier_id=<?= $filters['cashier_id'] ?? '' ?>&dealer_id=<?= $filters['dealer_id'] ?? '' ?>" class="btn btn-success" title="Excel formatida yuklash">
            <i class="fas fa-file-excel"></i> Excel
        </a>
        <a href="/new-pos/reports/export-pdf/monthly?month=<?= sprintf('%04d-%02d', $year, $month) ?>&cashier_id=<?= $filters['cashier_id'] ?? '' ?>&dealer_id=<?= $filters['dealer_id'] ?? '' ?>" class="btn btn-danger" title="PDF formatida yuklash">
            <i class="fas fa-file-pdf"></i> PDF
        </a>
        <a href="/new-pos/reports/print/monthly?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&cashier_id=<?= $filters['cashier_id'] ?? '' ?>&dealer_id=<?= $filters['dealer_id'] ?? '' ?>" class="btn btn-info" title="Chop etish" target="_blank">
            <i class="fas fa-print"></i> Print
        </a>
    </div>
</div>

<!-- Extra JavaScript (Chart.js va tooltip) -->
<?php if (!empty($chartData)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltips (agar Bootstrap mavjud bo'lsa)
        if (typeof bootstrap !== 'undefined') {
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"], [title]');
            tooltips.forEach(el => new bootstrap.Tooltip(el));
        }

        // Kunlik grafik
        const ctx = document.getElementById('dailyChart');
        if (ctx) {
            const labels = <?= json_encode($chartLabels) ?>;
            const data = <?= json_encode($chartData) ?>;
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kunlik savdo (so\'m)',
                        data: data,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102,126,234,0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
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
        }
    });
</script>
<?php endif; ?>