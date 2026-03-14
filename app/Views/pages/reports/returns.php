<!-- Page Title -->
<?php $title = 'Qaytarishlar hisobot'; ?>

<div class="page-header">
    <h1>Qaytarishlar hisobot</h1>
</div>

<div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
    <form class="d-flex flex-wrap gap-2" method="GET" action="/new-pos/reports/returns">
        <label class="form-label mb-0">Boshlang'ich sana:</label>
        <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>" required>
        <label class="form-label mb-0">Tugash sanasi:</label>
        <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>" required>
        <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Filtrlash</button>
    </form>
    <div class="ms-auto">
        <a href="/new-pos/reports/export-excel/returns?start_date=<?= urlencode($startDate) ?>&end_date=<?= urlencode($endDate) ?>" class="btn btn-success"><i class="fas fa-file-excel"></i> Excel</a>
        <a href="/new-pos/reports/export-pdf/returns?start_date=<?= urlencode($startDate) ?>&end_date=<?= urlencode($endDate) ?>" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Chek</th>
                <th>Kassir</th>
                <th>Mijoz</th>
                <th>Mahsulot</th>
                <th>Miqdor</th>
                <th>Summa</th>
                <th>Sabab</th>
                <th>Sana</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($returns)): ?>
                <tr><td colspan="9" class="text-center py-4">Ma'lumot topilmadi</td></tr>
            <?php else: ?>
                <?php foreach ($returns as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($item['chek_raqami'] ?? '') ?></td>
                        <td><?= htmlspecialchars($item['kassir_fio'] ?? '') ?></td>
                        <td><?= htmlspecialchars($item['mijoz_fio'] ?? '') ?></td>
                        <td><?= htmlspecialchars($item['mahsulot_nomi'] ?? '') ?></td>
                        <td><?= number_format($item['miqdor'] ?? 0, 3, ',', ' ') ?></td>
                        <td><?= number_format($item['summa'] ?? 0, 2, ',', ' ') ?> so'm</td>
                        <td><?= htmlspecialchars($item['sabab'] ?? '') ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($item['qaytarilgan_vaqt'] ?? 'now')) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
