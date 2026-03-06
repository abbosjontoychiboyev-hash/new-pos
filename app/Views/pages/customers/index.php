<!-- Page Title -->
<?php $title = 'Mijozlar'; ?>

<!-- Page Content -->
<div class="content-card">
    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" class="search-box">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Qidirish (ism yoki telefon)" 
                   value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Qidirish
            </button>
            <?php if (!empty($search)): ?>
                <a href="/new-pos/customers" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Tozalash
                </a>
            <?php endif; ?>
        </form>
        
        <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Kassir'])): ?>
            <a href="/new-pos/customers/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Yangi mijoz
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Customers Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mijoz</th>
                    <th>Telefon</th>
                    <th>Manzil</th>
                    <th>Qarz</th>
                    <th>Oxirgi savdo</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Hech qanday mijoz topilmadi</p>
                            <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Kassir'])): ?>
                                <a href="/new-pos/customers/create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Birinchi mijozni qo'shish
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $customer): ?>
                        <?php 
                        // Mijoz qarzini hisoblash
                        $debtModel = new \App\Models\Debt();
                        $debtInfo = $debtModel->getCustomerDebt($customer['id']);
                        $debtAmount = $debtInfo['jami_qarz'] ?? 0;
                        
                        // Oxirgi savdo vaqtini olish
                        $stmt = $this->db->prepare("
                            SELECT MAX(sotilgan_vaqt) as oxirgi_savdo 
                            FROM savdolar 
                            WHERE mijoz_id = ? AND holat = 'YAKUNLANGAN'
                        ");
                        $stmt->execute([$customer['id']]);
                        $lastSale = $stmt->fetch();
                        ?>
                        <tr>
                            <td><span class="badge bg-secondary">#<?= $customer['id'] ?></span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="customer-avatar">
                                        <?= strtoupper(substr($customer['fio'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($customer['fio']) ?></strong>
                                        <?php if (!empty($customer['izoh'])): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($customer['izoh']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($customer['telefon'])): ?>
                                    <a href="tel:<?= $customer['telefon'] ?>"><?= $customer['telefon'] ?></a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= $customer['manzil'] ?? '-' ?></td>
                            <td>
                                <?php if ($debtAmount > 0): ?>
                                    <span class="badge-debt"><?= number_format($debtAmount, 0, ',', ' ') ?> so'm</span>
                                <?php else: ?>
                                    <span class="badge-paid">0 so'm</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $lastSale && $lastSale['oxirgi_savdo'] ? date('d.m.Y', strtotime($lastSale['oxirgi_savdo'])) : '-' ?>
                            </td>
                            <td>
                                <a href="/new-pos/customers/edit/<?= $customer['id'] ?>" 
                                   class="btn btn-sm btn-warning btn-action"
                                   title="Tahrirlash"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <?php if ($debtAmount > 0): ?>
                                    <a href="/new-pos/debt/customer/<?= $customer['id'] ?>" 
                                       class="btn btn-sm btn-info btn-action"
                                       title="Qarz tarixi"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-credit-card"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($_SESSION['user']['rol_nomi'] == 'Admin'): ?>
                                    <form method="POST" 
                                          action="/new-pos/customers/delete/<?= $customer['id'] ?>" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Haqiqatan ham bu mijozni o\'chirmoqchimisiz?');">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                        <button type="submit" class="btn btn-sm btn-danger btn-action" title="O'chirish" data-bs-toggle="tooltip">
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
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $pagination['lastPage']; $i++): ?>
                    <li class="page-item <?= $i == $pagination['page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?><?= isset($search) && $search ? '&search=' . urlencode($search) : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .content-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
    
    .badge-debt {
        background: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-paid {
        background: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }
    
    .customer-avatar {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        margin-right: 10px;
        flex-shrink: 0;
    }
    
    .btn-action {
        padding: 5px 10px;
        margin: 0 2px;
        border-radius: 6px;
    }
    
    .pagination {
        margin-top: 20px;
        justify-content: center;
    }
    
    .page-link {
        color: #667eea;
        border: none;
        padding: 8px 15px;
        margin: 0 3px;
        border-radius: 8px;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltips.length > 0) {
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        }
    });
    
    // Format phone numbers (optional)
    document.querySelectorAll('a[href^="tel:"]').forEach(link => {
        link.addEventListener('click', function(e) {
            // Telefon raqamni bosganda hech qanday xabar chiqmaydi
        });
    });
</script>
<?php $extraJs = ob_get_clean(); ?>