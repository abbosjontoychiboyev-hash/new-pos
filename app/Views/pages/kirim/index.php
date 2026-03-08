<!-- Page Title -->
<?php $title = 'Kirimlar (Mahsulot qabul qilish)'; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-download"></i> Kirimlar</h5>
        <a href="/new-pos/kirim/create" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Yangi kirim
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sana</th>
                        <th>Yetkazib beruvchi</th>
                        <th>Kiritgan</th>
                        <th>Umumiy summa</th>
                        <th>Holat</th>
                        <th>Izoh</th>
                        <th>Amallar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kirimlar as $k): ?>
                    <tr>
                        <td>#<?= $k['id'] ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($k['kirim_vaqt'])) ?></td>
                        <td><?= htmlspecialchars($k['yetkazib_nomi'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($k['kiritgan_fio'] ?? '-') ?></td>
                        <td><?= number_format($k['umumiy_summa'], 0, ',', ' ') ?> so'm</td>
                        <td>
                            <?php if ($k['holat'] == 'QABUL_QILINDI'): ?>
                                <span class="badge bg-success">Qabul qilindi</span>
                            <?php elseif ($k['holat'] == 'QORALAMA'): ?>
                                <span class="badge bg-warning">Qoralama</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Bekor</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($k['izoh'] ?? '-') ?></td>
                        <td>
                            <a href="/new-pos/kirim/view/<?= $k['id'] ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Ko'rish
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>