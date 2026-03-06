<!-- Page Title -->
<?php $title = 'Foydalanuvchilar'; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .content-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .card-title i {
        color: #667eea;
        margin-right: 10px;
    }
    
    .table th {
        background: #f8f9fa;
    }
    
    .badge-success { background: #d4edda; color: #155724; }
    .badge-danger { background: #f8d7da; color: #721c24; }
    
    .btn-action {
        padding: 5px 10px;
        margin: 0 2px;
        border-radius: 6px;
    }
    
    .role-badge {
        display: inline-block;
        padding: 5px 12px;
        background: #e7f5ff;
        color: #004085;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-success {
            width: 100%;
        }
        
        .table th, .table td {
            white-space: nowrap;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Page Content -->
<!-- Roles Stats -->
<div class="row mb-4">
    <?php foreach ($roles as $role): ?>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($role['nomi']) ?></h5>
                <p class="card-text display-6"><?= $role['users_count'] ?></p>
                <small class="text-muted">foydalanuvchi</small>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Users Table -->
<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-users"></i> Foydalanuvchilar ro'yxati
        </div>
        <a href="/new-pos/settings/users/create" class="btn btn-success">
            <i class="fas fa-plus"></i> Yangi foydalanuvchi
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>F.I.O.</th>
                    <th>Login</th>
                    <th>Rol</th>
                    <th>Email/Telefon</th>
                    <th>Oxirgi kirish</th>
                    <th>Holat</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>#<?= $user['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($user['fio']) ?></strong>
                    </td>
                    <td><?= htmlspecialchars($user['login']) ?></td>
                    <td>
                        <span class="role-badge"><?= htmlspecialchars($user['rol_nomi']) ?></span>
                    </td>
                    <td>
                        <?= $user['email'] ?? '' ?><br>
                        <small><?= $user['telefon'] ?? '' ?></small>
                    </td>
                    <td>
                        <?= $user['oxirgi_kirish_vaqt'] ? date('d.m.Y H:i', strtotime($user['oxirgi_kirish_vaqt'])) : '-' ?>
                    </td>
                    <td>
                        <?php if ($user['faol']): ?>
                            <span class="badge bg-success">Faol</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Bloklangan</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/new-pos/settings/users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-warning btn-action" title="Tahrirlash">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <form method="POST" action="/new-pos/settings/users/delete/<?= $user['id'] ?>" style="display: inline;" onsubmit="return confirm('Bu foydalanuvchini o\'chirishni xohlaysizmi?');">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <button type="submit" class="btn btn-sm btn-danger btn-action" title="O'chirish">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>