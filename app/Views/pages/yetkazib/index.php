<!-- Page Title -->
<?php $title = 'Yetkazib beruvchilar (Dillerlar)'; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .supplier-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    
    .today-delivery {
        background: #e7f5ff;
        border-left: 4px solid #667eea;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .badge-debt {
        background: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-paid {
        background: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
    }
    
    .filter-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .search-box {
        flex: 1;
        display: flex;
        gap: 10px;
        min-width: 300px;
    }
    
    .btn-pay {
        background: #28a745;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 6px;
        transition: all 0.3s;
    }
    
    .btn-pay:hover {
        background: #218838;
        transform: translateY(-2px);
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Page Content -->

<!-- Bugungi keladiganlar -->
<?php if (!empty($todays)): ?>
<div class="today-delivery">
    <i class="fas fa-truck text-primary"></i>
    <strong>Bugun keladigan dillerlar:</strong>
    <?php foreach ($todays as $d): ?>
        <span class="badge bg-info me-1"><?= htmlspecialchars($d['nomi']) ?></span>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Filter Section -->
<div class="filter-section">
    <form method="GET" class="search-box">
        <input type="text" name="search" class="form-control" placeholder="Diller nomi bo'yicha qidirish..." 
               value="<?= htmlspecialchars($search ?? '') ?>">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Qidirish</button>
    </form>
    <a href="/new-pos/yetkazib/create" class="btn btn-success">
        <i class="fas fa-plus"></i> Yangi diller
    </a>
</div>

<!-- Flash Messages -->
<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['flash']['success'] ?></div>
    <?php unset($_SESSION['flash']['success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['flash']['error'] ?></div>
    <?php unset($_SESSION['flash']['error']); ?>
<?php endif; ?>

<!-- Dillerlar jadvali -->
<div class="supplier-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nomi</th>
                    <th>Telefon</th>
                    <th>Manzil</th>
                    <th>Qarz</th>
                    <th>Kelish kuni</th>
                    <th>Oxirgi to'lov</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($yetkazib)): ?>
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Hech qanday diller topilmadi</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($yetkazib as $d): ?>
                    <tr>
                        <td>#<?= $d['id'] ?></td>
                        <td><strong><?= htmlspecialchars($d['nomi']) ?></strong></td>
                        <td><?= htmlspecialchars($d['telefon'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($d['manzil'] ?? '-') ?></td>
                        <td>
                            <?php if ($d['qarz'] > 0): ?>
                                <span class="badge-debt"><?= number_format($d['qarz'], 0, ',', ' ') ?> so'm</span>
                            <?php else: ?>
                                <span class="badge-paid">0 so'm</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $d['kelish_kuni'] ?? '-' ?></td>
                        <td><?= $d['oxirgi_olingan_sana'] ? date('d.m.Y', strtotime($d['oxirgi_olingan_sana'])) : '-' ?></td>
                        <td>
                            <a href="/new-pos/yetkazib/edit/<?= $d['id'] ?>" class="btn btn-sm btn-warning" title="Tahrirlash">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($d['qarz'] > 0): ?>
                                <button class="btn btn-sm btn-success btn-pay" onclick="showPayModal(<?= $d['id'] ?>, '<?= htmlspecialchars($d['nomi']) ?>', <?= $d['qarz'] ?>)" title="Qarz to'lash">
                                    <i class="fas fa-money-bill-wave"></i>
                                </button>
                            <?php endif; ?>
                            <?php if ($_SESSION['user']['rol_nomi'] == 'Admin'): ?>
                                <form method="POST" action="/new-pos/yetkazib/delete/<?= $d['id'] ?>" style="display: inline;" onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?');">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" title="O'chirish">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if (isset($pagination) && $pagination['lastPage'] > 1): ?>
        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $pagination['lastPage']; $i++): ?>
                    <li class="page-item <?= $i == $pagination['page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- To'lov modal oynasi -->
<div class="modal fade" id="payModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Qarzni to'lash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="payForm">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-body">
                    <p><strong id="supplierName"></strong> ga to'lov qilish</p>
                    <p>Joriy qarz: <span id="currentDebt"></span> so'm</p>
                    <div class="mb-3">
                        <label class="form-label">To'lov summasi</label>
                        <input type="number" name="summa" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="submit" class="btn btn-primary">To'lash</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    function showPayModal(id, name, debt) {
        document.getElementById('supplierName').innerText = name;
        document.getElementById('currentDebt').innerText = debt.toLocaleString();
        document.getElementById('payForm').action = '/new-pos/yetkazib/pay-debt/' + id;
        new bootstrap.Modal(document.getElementById('payModal')).show();
    }
</script>
<?php $extraJs = ob_get_clean(); ?>