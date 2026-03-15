<!-- Page Title -->
<?php $title = 'Rollarni boshqarish'; ?>

<div class="page-header">
    <h1>Rollarni boshqarish</h1>
    <div class="page-actions">
        <a href="/<?= BASE_PATH ?>/settings/roles/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yangi rol
        </a>
    </div>
</div>

<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['flash']['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash']['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['flash']['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash']['error']); ?>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rol nomi</th>
                        <th>Izoh</th>
                        <th>Foydalanuvchilar soni</th>
                        <th>Yaratilgan</th>
                        <th>Amallar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $role): ?>
                        <tr>
                            <td><?= $role['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($role['nomi']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($role['izoh'] ?: 'Izoh yo\'q') ?></td>
                            <td>
                                <span class="badge bg-info"><?= $role['users_count'] ?> ta</span>
                            </td>
                            <td><?= date('d.m.Y', strtotime($role['yaratilgan_vaqt'])) ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/<?= BASE_PATH ?>/settings/roles/show/<?= $role['id'] ?>" class="btn btn-info" title="Ko'rish">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/<?= BASE_PATH ?>/settings/roles/edit/<?= $role['id'] ?>" class="btn btn-warning" title="Tahrirlash">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($role['nomi'] !== 'Admin'): ?>
                                        <form method="POST" action="/<?= BASE_PATH ?>/settings/roles/delete/<?= $role['id'] ?>" class="d-inline" onsubmit="return confirm('Haqiqatan ham bu rolni o\'chirmoqchimisiz?')">
                                            <input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>">
                                            <button type="submit" class="btn btn-danger" title="O'chirish">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>