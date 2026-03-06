<!-- Page Title -->
<?php $title = 'Subkategoriyalar'; ?>

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
    
    .filter-box {
        width: 250px;
    }
    
    .table th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        font-size: 14px;
        padding: 12px;
    }
    
    .table td {
        padding: 12px;
        vertical-align: middle;
        color: #333;
    }
    
    .badge-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }
    
    .badge-success { background: #d4edda; color: #155724; }
    .badge-danger { background: #f8d7da; color: #721c24; }
    .badge-info { background: #e7f5ff; color: #004085; }
    .badge-secondary { background: #e2e3e5; color: #383d41; }
    .badge-primary { background: #cce5ff; color: #004085; }
    
    .btn-action {
        padding: 5px 10px;
        margin: 0 2px;
        border-radius: 6px;
    }
    
    .category-badge {
        display: inline-block;
        padding: 5px 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .stats-badge {
        background: #e9ecef;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        color: #495057;
    }
    
    .drag-handle {
        cursor: move;
        color: #999;
    }
    
    .drag-handle:hover {
        color: #667eea;
    }
    
    .sortable-ghost {
        opacity: 0.5;
        background: #e7f5ff;
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
    }
    
    @media (max-width: 768px) {
        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            min-width: 100%;
        }
        
        .filter-box {
            width: 100%;
        }
        
        .table th, .table td {
            white-space: nowrap;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Auto close alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
    
    // Drag and drop sorting
    <?php if (empty($search) && empty($selectedCategory) && in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
    const sortableTable = document.getElementById('sortableSubcategories');
    if (sortableTable) {
        new Sortable(sortableTable, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                // Yangi tartibni saqlash
                const items = [];
                document.querySelectorAll('#sortableSubcategories tr').forEach((row, index) => {
                    const id = row.dataset.id;
                    if (id) {
                        items.push({
                            id: id,
                            tartib: index + 1
                        });
                    }
                });
                
                // Tartibni serverga yuborish
                fetch('/new-pos/subcategories/update-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        items: items,
                        csrf_token: '<?= csrf_token() ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tartib raqamlarini yangilash
                        items.forEach(item => {
                            const row = document.querySelector(`tr[data-id="${item.id}"]`);
                            const tartibSpan = row.querySelector('.badge.bg-info');
                            if (tartibSpan) {
                                tartibSpan.textContent = item.tartib;
                            }
                        });
                        
                        // Muvaffaqiyatli xabar
                        showNotification('Tartib saqlandi', 'success');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }
    <?php endif; ?>
    
    // Notification function
    function showNotification(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<div class="content-card">
    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" class="search-box">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Subkategoriya qidirish..." 
                   value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Qidirish
            </button>
            <?php if (!empty($search) || !empty($selectedCategory)): ?>
                <a href="/new-pos/subcategories" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Tozalash
                </a>
            <?php endif; ?>
        </form>
        
        <div class="filter-box">
            <select class="form-select" onchange="window.location.href=this.value">
                <option value="/new-pos/subcategories">Barcha kategoriyalar</option>
                <?php foreach ($categories as $cat): ?>
                    <?php 
                    $selected = (isset($selectedCategory) && $selectedCategory == $cat['id']) ? 'selected' : '';
                    $url = '/new-pos/subcategories?category=' . $cat['id'];
                    ?>
                    <option value="<?= $url ?>" <?= $selected ?>>
                        <?= htmlspecialchars($cat['nomi']) ?> (<?= $cat['subcategories_count'] ?? 0 ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
            <a href="/new-pos/subcategories/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Yangi subkategoriya
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Subcategories Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th width="50">Tartib</th>
                    <th>Nomi</th>
                    <th>Kategoriya</th>
                    <th>Izoh</th>
                    <th>Mahsulotlar</th>
                    <th>Holat</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody id="sortableSubcategories">
                <?php if (empty($subcategories)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Hech qanday subkategoriya topilmadi</p>
                            <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
                                <a href="/new-pos/subcategories/create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Yangi subkategoriya qo'shish
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($subcategories as $sub): ?>
                        <tr data-id="<?= $sub['id'] ?>" data-tartib="<?= $sub['tartib'] ?? 0 ?>">
                            <td><span class="badge bg-secondary">#<?= $sub['id'] ?></span></td>
                            <td>
                                <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
                                    <span class="drag-handle">
                                        <i class="fas fa-grip-vertical"></i>
                                    </span>
                                <?php endif; ?>
                                <span class="badge bg-info"><?= $sub['tartib'] ?? 0 ?></span>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($sub['nomi']) ?></strong>
                            </td>
                            <td>
                                <span class="category-badge">
                                    <i class="fas fa-tag"></i> <?= htmlspecialchars($sub['kategoriya_nomi'] ?? '-') ?>
                                </span>
                            </td>
                            <td>
                                <?= htmlspecialchars($sub['izoh'] ?? '-') ?>
                            </td>
                            <td>
                                <span class="stats-badge">
                                    <i class="fas fa-box"></i> <?= $sub['products_count'] ?? 0 ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($sub['faol']): ?>
                                    <span class="badge-status badge-success">Faol</span>
                                <?php else: ?>
                                    <span class="badge-status badge-danger">Faol emas</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/new-pos/products?category=<?= $sub['kategoriya_id'] ?>&subcategory=<?= $sub['id'] ?>" 
                                   class="btn btn-sm btn-info btn-action" 
                                   title="Mahsulotlar">
                                    <i class="fas fa-box"></i>
                                </a>
                                
                                <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
                                    <a href="/new-pos/subcategories/edit/<?= $sub['id'] ?>" 
                                       class="btn btn-sm btn-warning btn-action"
                                       title="Tahrirlash">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if ($_SESSION['user']['rol_nomi'] == 'Admin'): ?>
                                        <form method="POST" 
                                              action="/new-pos/subcategories/delete/<?= $sub['id'] ?>" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Haqiqatan ham bu subkategoriyani o\'chirmoqchimisiz?');">
                                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                            <button type="submit" class="btn btn-sm btn-danger btn-action" title="O'chirish">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
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
                        <a class="page-link" href="?page=<?= $i ?><?= isset($search) && $search ? '&search=' . urlencode($search) : '' ?><?= isset($selectedCategory) && $selectedCategory ? '&category=' . $selectedCategory : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>