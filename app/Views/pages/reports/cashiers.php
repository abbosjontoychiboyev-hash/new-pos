<!-- Page Title -->
<?php $title = 'Kassirlar hisobot'; ?>

<div class="page-header">
    <h1>Kassirlar hisobot</h1>
</div>

<div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
    <form class="d-flex flex-wrap gap-2" method="GET" action="/new-pos/reports/cashiers">
        <label class="form-label mb-0">Boshlang'ich sana:</label>
        <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>" required>
        <label class="form-label mb-0">Tugash sanasi:</label>
        <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>" required>
        <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Filtrlash</button>
    </form>
    <div class="ms-auto">
        <a href="/new-pos/reports/export-excel/cashiers?start_date=<?= urlencode($startDate) ?>&end_date=<?= urlencode($endDate) ?>" class="btn btn-success"><i class="fas fa-file-excel"></i> Excel</a>
        <a href="/new-pos/reports/export-pdf/cashiers?start_date=<?= urlencode($startDate) ?>&end_date=<?= urlencode($endDate) ?>" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Kassir</th>
                <th>Savdolar</th>
                <th>Jami savdo</th>
                <th>O'rtacha chek</th>
                <th>Chegirma</th>
                <th>Nasiya savdo</th>
                <th>Jami qarz</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($cashiers)): ?>
                <tr><td colspan="8" class="text-center py-4">Ma'lumot topilmadi</td></tr>
            <?php else: ?>
                <?php foreach ($cashiers as $index => $cashier): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($cashier['fio'] ?? '') ?></td>
                        <td><?= number_format($cashier['savdolar_soni'] ?? 0, 0, ',', ' ') ?></td>
                        <td><?= number_format($cashier['jami_savdo'] ?? 0, 0, ',', ' ') ?> so'm</td>
                        <td><?= number_format($cashier['ortacha_chek'] ?? 0, 0, ',', ' ') ?> so'm</td>
                        <td><?= number_format($cashier['jami_chegirma'] ?? 0, 0, ',', ' ') ?> so'm</td>
                        <td><?= number_format($cashier['nasiya_soni'] ?? 0, 0, ',', ' ') ?></td>
                        <td><?= number_format($cashier['jami_qarz'] ?? 0, 0, ',', ' ') ?> so'm</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
