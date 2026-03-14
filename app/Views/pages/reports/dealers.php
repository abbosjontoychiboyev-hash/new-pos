<!-- Page Title -->
<?php $title = 'Dillerlar hisobot'; ?>

<div class="page-header">
    <h1>Dillerlar hisobot</h1>
</div>

<div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
    <div class="ms-auto">
        <a href="/new-pos/reports/export-excel/dealers" class="btn btn-success"><i class="fas fa-file-excel"></i> Excel</a>
        <a href="/new-pos/reports/export-pdf/dealers" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Diller nomi</th>
                <th>Telefon</th>
                <th>Jami olingan</th>
                <th>Jami to‘langan</th>
                <th>Qarz</th>
                <th>Oxirgi to‘lov</th>
                <th>Oxirgi olish</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($dealers)): ?>
                <tr><td colspan="8" class="text-center py-4">Ma'lumot topilmadi</td></tr>
            <?php else: ?>
                <?php foreach ($dealers as $index => $dealer): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($dealer['nomi'] ?? '') ?></td>
                        <td><?= htmlspecialchars($dealer['telefon'] ?? '') ?></td>
                        <td><?= number_format($dealer['jami_olingan'] ?? 0, 0, ',', ' ') ?> so'm</td>
                        <td><?= number_format($dealer['jami_tolangan'] ?? 0, 0, ',', ' ') ?> so'm</td>
                        <td><?= number_format($dealer['qarz'] ?? 0, 0, ',', ' ') ?> so'm</td>
                        <td><?= !empty($dealer['oxirgi_tolov_sana']) ? date('d.m.Y', strtotime($dealer['oxirgi_tolov_sana'])) : '-' ?></td>
                        <td><?= !empty($dealer['oxirgi_olingan_sana']) ? date('d.m.Y', strtotime($dealer['oxirgi_olingan_sana'])) : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
