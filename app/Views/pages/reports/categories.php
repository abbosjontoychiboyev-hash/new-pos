<!-- Page Title -->
<?php $title = 'Kategoriyalar hisobot'; ?>

<div class="page-header">
    <h1>Kategoriyalar hisobot</h1>
</div>

<div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
    <form class="d-flex flex-wrap gap-2" method="GET" action="/new-pos/reports/categories">
        <label class="form-label mb-0">Boshlang'ich sana:</label>
        <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>" required>
        <label class="form-label mb-0">Tugash sanasi:</label>
        <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>" required>
        <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Filtrlash</button>
    </form>
    <div class="ms-auto">
        <a href="/new-pos/reports/export-excel/categories?start_date=<?= urlencode($startDate) ?>&end_date=<?= urlencode($endDate) ?>" class="btn btn-success"><i class="fas fa-file-excel"></i> Excel</a>
        <a href="/new-pos/reports/export-pdf/categories?start_date=<?= urlencode($startDate) ?>&end_date=<?= urlencode($endDate) ?>" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Kategoriya</th>
                <th>Savdolar soni</th>
                <th>Mahsulotlar soni</th>
                <th>Jami soni</th>
                <th>Jami summa</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categories)): ?>
                <tr><td colspan="6" class="text-center py-4">Ma'lumot topilmadi</td></tr>
            <?php else: ?>
                <?php foreach ($categories as $index => $cat): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($cat['nomi'] ?? '') ?></td>
                        <td><?= number_format($cat['savdolar_soni'] ?? 0, 0, ',', ' ') ?></td>
                        <td><?= number_format($cat['mahsulotlar_soni'] ?? 0, 0, ',', ' ') ?></td>
                        <td><?= number_format($cat['jami_soni'] ?? 0, 0, ',', ' ') ?></td>
                        <td><?= number_format($cat['jami_summa'] ?? 0, 0, ',', ' ') ?> so'm</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
