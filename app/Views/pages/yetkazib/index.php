<!-- Page Title -->
<?php $title = 'Dillerlar (Yetkazib beruvchilar)'; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .debt-badge {
        background: #dc3545;
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .last-purchase {
        font-size: 12px;
        color: #666;
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Qarzdor dillerlar (tezkor ko‘rinish) -->
<?php if (!empty($qarzdorlar)): ?>
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Qarzdor dillerlar:</strong>
    <?php foreach ($qarzdorlar as $q): ?>
        <a href="/new-pos/yetkazib/view/<?= $q['id'] ?>" class="alert-link"><?= htmlspecialchars($q['nomi']) ?> (<?= number_format($q['qarz'], 0, ',', ' ') ?> so‘m)</a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-truck"></i> Dillerlar ro‘yxati</h5>
        <a href="/new-pos/yetkazib/create" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Yangi diller</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nomi</th>
                        <th>Telefon</th>
                        <th>Oxirgi kirim</th>
                        <th>Oxirgi kirim summasi</th>
                        <th>Jami qarz</th>
                        <th>Oxirgi to‘lov</th>
                        <th>Amallar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dillerlar as $d): ?>
                    <tr>
                        <td>#<?= $d['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($d['nomi']) ?></strong>
                            <?php if (!empty($d['izoh'])): ?><br><small><?= htmlspecialchars($d['izoh']) ?></small><?php endif; ?>
                        </td>
                        <td><?= $d['telefon'] ?? '-' ?></td>
                        <td>
                            <?php if ($d['last_kirim']): ?>
                                <?= date('d.m.Y', strtotime($d['last_kirim']['kirim_vaqt'])) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($d['last_kirim']): ?>
                                <?= number_format($d['last_kirim']['jami_summa'] ?? 0, 0, ',', ' ') ?> so‘m
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($d['qarz'] > 0): ?>
                                <span class="debt-badge"><?= number_format($d['qarz'], 0, ',', ' ') ?> so‘m</span>
                            <?php else: ?>
                                0 so‘m
                            <?php endif; ?>
                        </td>
                        <td><?= $d['oxirgi_tolov_sana'] ? date('d.m.Y', strtotime($d['oxirgi_tolov_sana'])) : '-' ?></td>
                        <td>
                            <a href="/new-pos/yetkazib/show/<?= $d['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Ko‘rish</a>
                            <a href="/new-pos/yetkazib/edit/<?= $d['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <?php if ($_SESSION['user']['rol_nomi'] == 'Admin'): ?>
                            <form method="POST" action="/new-pos/yetkazib/delete/<?= $d['id'] ?>" style="display:inline;" onsubmit="return confirm('Haqiqatan ham o‘chirmoqchimisiz?')">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>