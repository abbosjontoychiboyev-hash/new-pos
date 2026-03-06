<!-- Page Title -->
<?php $title = 'Mijoz qarzi - ' . htmlspecialchars($customer['fio']); ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .customer-header {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .customer-avatar {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 30px;
    }
    
    .customer-info h2 {
        margin: 0 0 5px 0;
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }
    
    .customer-info p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }
    
    .customer-info p i {
        width: 20px;
        color: #667eea;
    }
    
    .debt-summary {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .total-debt {
        font-size: 32px;
        font-weight: 700;
        color: #dc3545;
    }
    
    .total-debt small {
        font-size: 14px;
        font-weight: normal;
        color: #666;
        margin-left: 10px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
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
    .stat-card.warning { border-left-color: #ffc107; }
    .stat-card.info { border-left-color: #17a2b8; }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: #666;
        font-size: 14px;
    }
    
    .table-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
        flex-wrap: wrap;
        gap: 10px;
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
    
    .btn-payment {
        background: #28a745;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-payment:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40,167,69,0.3);
    }
    
    .badge-debt {
        background: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-paid {
        background: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    .badge-partial {
        background: #ffc107;
        color: #333;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .customer-header {
            flex-direction: column;
            text-align: center;
        }
        
        .debt-summary {
            flex-direction: column;
            text-align: center;
        }
        
        .table th, .table td {
            white-space: nowrap;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Page Content -->
<!-- Customer Header -->
<div class="customer-header">
    <div class="customer-avatar">
        <i class="fas fa-user"></i>
    </div>
    <div class="customer-info">
        <h2><?= htmlspecialchars($customer['fio']) ?></h2>
        <p><i class="fas fa-phone"></i> <?= $customer['telefon'] ?? 'Telefon raqam kiritilmagan' ?></p>
        <p><i class="fas fa-map-marker-alt"></i> <?= $customer['manzil'] ?? 'Manzil kiritilmagan' ?></p>
    </div>
</div>

<!-- Debt Summary -->
<div class="debt-summary">
    <div>
        <span class="total-debt"><?= number_format($debtInfo['jami_qarz'] ?? 0, 0, ',', ' ') ?> so'm</span>
        <small>Jami qarz</small>
    </div>
    
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-value"><?= $stats['jami_savdolar'] ?? 0 ?></div>
        <div class="stat-label">Jami savdolar</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-value"><?= $debtInfo['qarzli_savdolar'] ?? 0 ?></div>
        <div class="stat-label">Qarzli savdolar</div>
    </div>
    <div class="stat-card info">
        <div class="stat-value"><?= $debtInfo['oxirgi_savdo'] ? date('d.m.Y', strtotime($debtInfo['oxirgi_savdo'])) : '-' ?></div>
        <div class="stat-label">Oxirgi savdo</div>
    </div>
</div>

<!-- Debt Sales Table -->
<div class="table-card">
    <div class="table-header">
        <div class="table-title">
            <i class="fas fa-credit-card text-danger"></i> Qarzli savdolar
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Chek №</th>
                    <th>Sana</th>
                    <th>Umumiy summa</th>
                    <th>To'langan</th>
                    <th>Qolgan qarz</th>
                    <th>Holat</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($history)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted">Mijozning qarzi yo'q</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($history as $item): ?>
                    <tr>
                        <td><strong><?= $item['chek_raqami'] ?></strong></td>
                        <td><?= date('d.m.Y H:i', strtotime($item['sotilgan_vaqt'])) ?></td>
                        <td><?= number_format($item['umumiy_summa'], 0, ',', ' ') ?> so'm</td>
                        <td><?= number_format($item['boshlangich_tolov'], 0, ',', ' ') ?> so'm</td>
                        <td class="text-danger fw-bold"><?= number_format($item['qolgan_qarz'], 0, ',', ' ') ?> so'm</td>
                        <td>
                            <?php if ($item['tolov_holati'] == 'NASIYA'): ?>
                                <span class="badge badge-debt">Nasiya</span>
                            <?php elseif ($item['tolov_holati'] == 'QISMAN'): ?>
                                <span class="badge badge-partial">Qisman</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/new-pos/debt/payment/<?= $item['savdo_id'] ?>" class="btn btn-sm btn-success">
                                <i class="fas fa-money-bill-wave"></i> To'lov
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Payment History Table -->
<div class="table-card">
    <div class="table-header">
        <div class="table-title">
            <i class="fas fa-history"></i> To'lovlar tarixi
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Sana</th>
                    <th>Chek №</th>
                    <th>To'lov summasi</th>
                    <th>To'lov usuli</th>
                    <th>Izoh</th>
                    <th>Kassir</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Hali to'lovlar yo'q</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?= date('d.m.Y H:i', strtotime($payment['tolangan_vaqt'])) ?></td>
                        <td><strong><?= $payment['chek_raqami'] ?></strong></td>
                        <td class="text-success fw-bold">+<?= number_format($payment['summa'], 0, ',', ' ') ?> so'm</td>
                        <td>
                            <?php 
                            $usul = $payment['usul'];
                            if ($usul == 'NAQD') echo 'Naqd';
                            elseif ($usul == 'KARTA') echo 'Karta';
                            elseif ($usul == 'OTKAZMA') echo 'Pul o\'tkazma';
                            else echo $usul;
                            ?>
                        </td>
                        <td><?= htmlspecialchars($payment['izoh'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($payment['qabul_qilgan_fio'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>