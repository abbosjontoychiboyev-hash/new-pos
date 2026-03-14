<!-- Page Title -->
<?php $title = 'Qarzdorlar hisobot'; ?>

<div class="page-header">
    <h1>Qarzdorlar hisobot</h1>
</div>

<div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
    <div class="ms-auto">
        <a href="/new-pos/reports/export-excel/debtors" class="btn btn-success"><i class="fas fa-file-excel"></i> Excel</a>
        <a href="/new-pos/reports/export-pdf/debtors" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Mijoz</th>
                <th>Telefon</th>
                <th>Qarzli savdolar</th>
                <th>Jami qarz</th>
                <th>Oxirgi savdo</th>
                <th>Kechikkan kun</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($debtors)): ?>
                <tr><td colspan="7" class="text-center py-4">Ma'lumot topilmadi</td></tr>
            <?php else: ?>
                <?php foreach ($debtors as $index => $debtor): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($debtor['fio'] ?? '') ?></td>
                        <td><?= htmlspecialchars($debtor['telefon'] ?? '') ?></td>
                        <td><?= number_format($debtor['qarzli_savdolar'] ?? 0, 0, ',', ' ') ?></td>
                        <td><?= number_format($debtor['jami_qarz'] ?? 0, 0, ',', ' ') ?> so'm</td>
                        <td><?= date('d.m.Y', strtotime($debtor['oxirgi_savdo'] ?? 'now')) ?></td>
                        <td><?= number_format($debtor['kechikkan_kun'] ?? 0, 0, ',', ' ') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
