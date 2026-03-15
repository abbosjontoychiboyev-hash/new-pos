<!-- Page Title -->
<?php $title = 'Rol tafsilotlari'; ?>

<div class="page-header">
    <h1>Rol tafsilotlari</h1>
    <div class="page-actions">
        <a href="/<?= BASE_PATH ?>/settings/roles" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
        <a href="/<?= BASE_PATH ?>/settings/roles/edit/<?= $role['id'] ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Tahrirlash
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Rol ma'lumotlari</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Rol nomi:</th>
                        <td>
                            <span class="badge bg-primary fs-6"><?= htmlspecialchars($role['nomi']) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Izoh:</th>
                        <td><?= htmlspecialchars($role['izoh'] ?: 'Izoh yo\'q') ?></td>
                    </tr>
                    <tr>
                        <th>Foydalanuvchilar soni:</th>
                        <td>
                            <span class="badge bg-info fs-6"><?= $userCount ?> ta</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Yaratilgan:</th>
                        <td><?= date('d.m.Y H:i', strtotime($role['yaratilgan_vaqt'])) ?></td>
                    </tr>
                    <tr>
                        <th>Yangilangan:</th>
                        <td><?= date('d.m.Y H:i', strtotime($role['yangilangan_vaqt'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Foydalanuvchilar</h5>
            </div>
            <div class="card-body">
                <?php if ($userCount > 0): ?>
                    <p class="text-muted">Bu rolga tegishli <?= $userCount ?> ta foydalanuvchi mavjud.</p>
                    <a href="/<?= BASE_PATH ?>/settings/users" class="btn btn-sm btn-outline-primary">
                        Foydalanuvchilarni ko'rish
                    </a>
                <?php else: ?>
                    <p class="text-muted">Bu rolga hali foydalanuvchi biriktirilmagan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>