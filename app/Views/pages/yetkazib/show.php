<!-- Page Title -->
<?php $title = 'Diller: ' . htmlspecialchars($diller['nomi']); ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .summary-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
    }
    .summary-item {
        display: inline-block;
        margin-right: 30px;
    }
    .summary-label {
        font-size: 12px;
        color: #666;
    }
    .summary-value {
        font-size: 20px;
        font-weight: 700;
    }
    .debt {
        color: #dc3545;
    }
    .paid {
        color: #28a745;
    }
    .nav-tabs .nav-link {
        color: #495057;
    }
    .nav-tabs .nav-link.active {
        font-weight: 600;
        color: #667eea;
        border-bottom: 3px solid #667eea;
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Diller asosiy ma'lumotlari -->
<div class="card mb-4">
    <div class="card-header">
        <h5><i class="fas fa-truck"></i> <?= htmlspecialchars($diller['nomi']) ?></h5>
        <a href="/new-pos/yetkazib" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Orqaga</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <strong>Telefon:</strong> <?= $diller['telefon'] ?? '-' ?>
            </div>
            <div class="col-md-3">
                <strong>Manzil:</strong> <?= $diller['manzil'] ?? '-' ?>
            </div>
            <div class="col-md-3">
                <strong>Kelish kuni:</strong> <?= $diller['kelish_kuni'] ?? '-' ?>
            </div>
            <div class="col-md-3">
                <strong>To‘lash muddati:</strong> <?= $diller['tolash_muddati'] ? $diller['tolash_muddati'] . ' kun' : '-' ?>
            </div>
        </div>
        <?php if (!empty($diller['izoh'])): ?>
        <div class="row mt-2">
            <div class="col-12"><strong>Izoh:</strong> <?= nl2br(htmlspecialchars($diller['izoh'])) ?></div>
        </div>
        <?php endif; ?>
        <?php if (!empty($diller['tolash_eslatma'])): ?>
        <div class="row mt-2">
            <div class="col-12"><strong>To‘lov eslatmasi:</strong> <?= nl2br(htmlspecialchars($diller['tolash_eslatma'])) ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Xulosa kartochkasi -->
<div class="summary-card">
    <div class="summary-item">
        <div class="summary-label">Jami olingan</div>
        <div class="summary-value"><?= number_format($diller['jami_olingan'] ?? 0, 0, ',', ' ') ?> so‘m</div>
    </div>
    <div class="summary-item">
        <div class="summary-label">Jami to‘langan</div>
        <div class="summary-value paid"><?= number_format($diller['jami_tolangan'] ?? 0, 0, ',', ' ') ?> so‘m</div>
    </div>
    <div class="summary-item">
        <div class="summary-label">Qolgan qarz</div>
        <div class="summary-value debt"><?= number_format($diller['qarz'] ?? 0, 0, ',', ' ') ?> so‘m</div>
    </div>
    <div class="summary-item">
        <div class="summary-label">Oxirgi kirim</div>
        <div class="summary-value"><?= $diller['oxirgi_olingan_sana'] ? date('d.m.Y', strtotime($diller['oxirgi_olingan_sana'])) : '-' ?></div>
    </div>
    <div class="summary-item">
        <div class="summary-label">Oxirgi to‘lov</div>
        <div class="summary-value"><?= $diller['oxirgi_tolov_sana'] ? date('d.m.Y', strtotime($diller['oxirgi_tolov_sana'])) : '-' ?></div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="kirimlar-tab" data-bs-toggle="tab" data-bs-target="#kirimlar" type="button" role="tab">Kirimlar</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tolovlar-tab" data-bs-toggle="tab" data-bs-target="#tolovlar" type="button" role="tab">To‘lovlar</button>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    <!-- Kirimlar tabi -->
    <div class="tab-pane fade show active" id="kirimlar" role="tabpanel">
        <div class="card mt-3">
            <div class="card-header">
                <h6>Kelib tushgan mahsulotlar</h6>
            </div>
            <div class="card-body">
                <?php if (empty($kirimlar)): ?>
                    <p class="text-muted">Hali hech qanday kirim qayd etilmagan.</p>
                <?php else: ?>
                    <?php foreach ($kirimlar as $kirim): ?>
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>Kirim #<?= $kirim['id'] ?></strong> – <?= date('d.m.Y H:i', strtotime($kirim['kirim_vaqt'])) ?>
                                (Jami: <?= number_format($kirim['umumiy_summa'], 0, ',', ' ') ?> so‘m)
                                <span class="badge bg-info"><?= $kirim['mahsulotlar_soni'] ?> ta mahsulot</span>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Mahsulot</th>
                                            <th>Soni</th>
                                            <th>Kelish narxi</th>
                                            <th>Summa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($kirim['mahsulotlar'] as $m): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($m['nomi']) ?></td>
                                            <td><?= $m['soni'] ?> <?= $m['birlik'] ?></td>
                                            <td><?= number_format($m['birlik_kelish_narxi'], 0, ',', ' ') ?> so‘m</td>
                                            <td><?= number_format($m['qator_summa'], 0, ',', ' ') ?> so‘m</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- To‘lovlar tabi -->
    <div class="tab-pane fade" id="tolovlar" role="tabpanel">
        <div class="card mt-3">
            <div class="card-header">
                <h6>To‘lovlar tarixi</h6>
                <a href="/new-pos/yetkazib/add-payment/<?= $diller['id'] ?>" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Yangi to‘lov</a>
            </div>
            <div class="card-body">
                <?php if (empty($tolovlar)): ?>
                    <p class="text-muted">Hali to‘lov qilinmagan.</p>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sana</th>
                                <th>Summa</th>
                                <th>To‘lov usuli</th>
                                <th>Izoh</th>
                                <th>Qabul qilgan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tolovlar as $t): ?>
                            <tr>
                                <td><?= date('d.m.Y H:i', strtotime($t['sana'])) ?></td>
                                <td class="text-success fw-bold">+<?= number_format($t['summa'], 0, ',', ' ') ?> so‘m</td>
                                <td>
                                    <?php if ($t['usul'] == 'NAQD'): ?>Naqd
                                    <?php elseif ($t['usul'] == 'KARTA'): ?>Karta
                                    <?php else: ?>O‘tkazma<?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($t['izoh'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($t['qabul_qilgan_fio'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>