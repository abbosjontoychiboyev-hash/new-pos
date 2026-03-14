<!-- Page Title -->
<?php $title = 'Smеna hisobot'; ?>

<div class="page-header">
    <h1>Smеna hisobot</h1>
</div>

<div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
    <form class="d-flex flex-wrap gap-2" method="GET" action="/new-pos/reports/shifts">
        <label class="form-label mb-0">Boshlang'ich sana:</label>
        <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>" required>
        <label class="form-label mb-0">Tugash sanasi:</label>
        <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>" required>
        <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Filtrlash</button>
    </form>
    <div class="ms-auto">
        <a href="/new-pos/reports/export-excel/shifts?start_date=<?= urlencode($startDate) ?>&end_date=<?= urlencode($endDate) ?>" class="btn btn-success"><i class="fas fa-file-excel"></i> Excel</a>
        <a href="/new-pos/reports/export-pdf/shifts?start_date=<?= urlencode($startDate) ?>&end_date=<?= urlencode($endDate) ?>" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Kassir</th>
                <th>Ochilish</th>
                <th>Yopilish</th>
                <th>Boshlang'ich naqd</th>
                <th>Naqd tushum</th>
                <th>Qaytarish</th>
                <th>Diller to‘lovlari</th>
                <th>Kutilgan naqd</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($shifts)): ?>
                <tr><td colspan="9" class="text-center py-4">Ma'lumot topilmadi</td></tr>
            <?php else: ?>
                <?php foreach ($shifts as $index => $shift): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($shift['kassir_fio'] ?? '') ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($shift['ochilgan_vaqt'] ?? 'now')) ?></td>
                        <td><?= !empty($shift['yopilgan_vaqt']) ? date('d.m.Y H:i', strtotime($shift['yopilgan_vaqt'])) : '-' ?></td>
                        <td><?= number_format($shift['ochilish_naqd'] ?? 0, 2, ',', ' ') ?> so'm</td>
                        <td><?= number_format($shift['jami_naqd_tolov'] ?? 0, 2, ',', ' ') ?> so'm</td>
                        <td><?= number_format($shift['qaytarilgan_summa'] ?? 0, 2, ',', ' ') ?> so'm</td>
                        <td><?= number_format($shift['diller_tolovlari'] ?? 0, 2, ',', ' ') ?> so'm</td>
                        <td><?= number_format($shift['expected_cash'] ?? 0, 2, ',', ' ') ?> so'm</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
