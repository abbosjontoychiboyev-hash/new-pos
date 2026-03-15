<!-- Page Title -->
<?php $title = 'Foydalanuvchi tafsilotlari'; ?>

<div class="page-header">
    <h1>Foydalanuvchi tafsilotlari</h1>
    <div class="page-actions">
        <a href="/<?= BASE_PATH ?>/settings/users" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
        <a href="/<?= BASE_PATH ?>/settings/users/edit/<?= $user['id'] ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Tahrirlash
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Foydalanuvchi ma'lumotlari</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">FIO:</th>
                        <td><?= htmlspecialchars($user['fio']) ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?= htmlspecialchars($user['email'] ?: 'Kiritilmagan') ?></td>
                    </tr>
                    <tr>
                        <th>Telefon:</th>
                        <td><?= htmlspecialchars($user['telefon'] ?: 'Kiritilmagan') ?></td>
                    </tr>
                    <tr>
                        <th>Login:</th>
                        <td><?= htmlspecialchars($user['login']) ?></td>
                    </tr>
                    <tr>
                        <th>Rol:</th>
                        <td>
                            <span class="badge bg-primary"><?= htmlspecialchars($user['rol_nomi']) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Holati:</th>
                        <td>
                            <?php if ($user['faol']): ?>
                                <span class="badge bg-success">Faol</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Nofaol</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Oxirgi kirish:</th>
                        <td>
                            <?php if ($user['oxirgi_kirish_vaqt']): ?>
                                <?= date('d.m.Y H:i', strtotime($user['oxirgi_kirish_vaqt'])) ?>
                            <?php else: ?>
                                Hech qachon kirmagan
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Yaratilgan:</th>
                        <td><?= date('d.m.Y H:i', strtotime($user['yaratilgan_vaqt'])) ?></td>
                    </tr>
                    <tr>
                        <th>Yangilangan:</th>
                        <td><?= date('d.m.Y H:i', strtotime($user['yangilangan_vaqt'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistika</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Bu foydalanuvchi uchun qo'shimcha statistika kelajakda qo'shiladi.</p>
            </div>
        </div>
    </div>
</div>