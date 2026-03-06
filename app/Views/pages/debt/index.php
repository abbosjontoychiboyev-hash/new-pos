<!-- Page Title -->
<?php $title = 'Qarzdorlar'; ?>

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
                            <a href="/new-pos/debt/customer/<?= $debtor['id'] ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Qarz tarixini ko'rish">
                                <i class="fas fa-eye"></i> Tarix
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
                    <td><strong><?= $item['chek_raqami'] ?></strong></td>
                    <td class="text-danger fw-bold"><?= number_format($item['qarz_summa'], 0, ',', ' ') ?> so'm</td>
                    <td><?= date('d.m.Y', strtotime($item['sotilgan_vaqt'])) ?></td>
                    <td><span class="badge bg-danger"><?= $item['kechikkan_kun'] ?> kun</span></td>
                    <td>
                        <a href="/new-pos/debt/payment/<?= $item['id'] ?>" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="To'lov qabul qilish">
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
    .stat-card.danger { border-left-color: #dc3545; }
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
        margin-left: 5px;
        display: inline-block;
    }
    
    .badge-debt {
        background: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .table th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
    }
    
    .table td {
        vertical-align: middle;
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltips.length > 0) {
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>
