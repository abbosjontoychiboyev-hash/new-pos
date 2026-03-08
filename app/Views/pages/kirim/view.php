<!-- Page Title -->
<?php $title = 'Kirim #' . $kirim['id']; ?>

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-file-invoice"></i> Kirim #<?= $kirim['id'] ?></h5>
        <a href="/new-pos/kirim" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <strong>Sana:</strong> <?= date('d.m.Y H:i', strtotime($kirim['kirim_vaqt'])) ?>
            </div>
            <div class="col-md-3">
                <strong>Yetkazib beruvchi:</strong> <?= htmlspecialchars($kirim['yetkazib_nomi'] ?? '-') ?>
            </div>
            <div class="col-md-3">
                <strong>Kiritgan:</strong> <?= htmlspecialchars($kirim['kiritgan_fio'] ?? '-') ?>
            </div>
            <div class="col-md-3">
                <strong>Holat:</strong> 
                <?php if ($kirim['holat'] == 'QABUL_QILINDI'): ?>
                    <span class="badge bg-success">Qabul qilindi</span>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!empty($kirim['izoh'])): ?>
        <div class="row mt-2">
            <div class="col-12"><strong>Izoh:</strong> <?= htmlspecialchars($kirim['izoh']) ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6><i class="fas fa-boxes"></i> Mahsulotlar</h6>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Mahsulot</th>
                    <th>Shtrix kod</th>
                    <th>Soni</th>
                    <th>Kelish narxi</th>
                    <th>Summa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tarkib as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['nomi']) ?></td>
                    <td><?= $t['shtrix_kod'] ?></td>
                    <td><?= $t['soni'] ?> <?= $t['birlik'] ?></td>
                    <td><?= number_format($t['birlik_kelish_narxi'], 0, ',', ' ') ?> so'm</td>
                    <td><?= number_format($t['qator_summa'], 0, ',', ' ') ?> so'm</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Jami:</th>
                    <th><?= number_format($kirim['umumiy_summa'], 0, ',', ' ') ?> so'm</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>