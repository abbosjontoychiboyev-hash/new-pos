<!-- Page Title -->
<?php $title = 'Kategoriyalar'; ?>

<!-- Page Content -->
<div class="content-card">
    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" class="search-box">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Kategoriya qidirish..." 
                   value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Qidirish
            </button>
            <?php if (!empty($search)): ?>
                <a href="/new-pos/categories" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Tozalash
                </a>
            <?php endif; ?>
        </form>
        
        <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
            <a href="/new-pos/categories/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Yangi kategoriya
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Categories Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th width="50">Tartib</th>
                    <th>Nomi</th>
                    <th>Izoh</th>
                    <th>Subkategoriyalar</th>
                    <th>Mahsulotlar</th>
                    <th>Holat</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody id="sortableCategories">
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Hech qanday kategoriya topilmadi</p>
                            <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
                                <a href="/new-pos/categories/create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Birinchi kategoriyani qo'shish
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                        <tr data-id="<?= $category['id'] ?>" data-tartib="<?= $category['tartib'] ?? 0 ?>">
                            <td><span class="badge bg-secondary">#<?= $category['id'] ?></span></td>
                            <td>
                                <?php if (empty($search) && in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
                                    <span class="drag-handle">
                                        <i class="fas fa-grip-vertical"></i>
                                    </span>
                                <?php endif; ?>
                                <span class="badge bg-info"><?= $category['tartib'] ?? 0 ?></span>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($category['nomi']) ?></strong>
                            </td>
                            <td>
                                <?= htmlspecialchars($category['izoh'] ?? '-') ?>
                            </td>
                            <td>
                                <span class="badge bg-secondary category-stats">
                                    <i class="fas fa-folder-open"></i> 
                                    <?= $category['subcategories_count'] ?? 0 ?>
                                </span>
                                <?php if (($category['subcategories_count'] ?? 0) > 0): ?>
                                    <a href="/new-pos/subcategories?category=<?= $category['id'] ?>" class="btn btn-sm btn-link">
                                        Ko'rish
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-info category-stats">
                                    <i class="fas fa-box"></i> 
                                    <?= $category['products_count'] ?? 0 ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($category['faol']): ?>
                                    <span class="badge badge-success">Faol</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Faol emas</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/new-pos/subcategories?category=<?= $category['id'] ?>" 
                                   class="btn btn-sm btn-info btn-action" 
                                   title="Subkategoriyalar"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-folder-open"></i>
                                </a>
                                
                                <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
                                    <a href="/new-pos/categories/edit/<?= $category['id'] ?>" 
                                       class="btn btn-sm btn-warning btn-action"
                                       title="Tahrirlash"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if ($_SESSION['user']['rol_nomi'] == 'Admin'): ?>
                                        <form method="POST" 
                                              action="/new-pos/categories/delete/<?= $category['id'] ?>" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Haqiqatan ham bu kategoriyani o\'chirmoqchimisiz? Bu kategoriyaga bog\'liq barcha ma\'lumotlar o\'chiriladi.');">
                                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                            <button type="submit" class="btn btn-sm btn-danger btn-action" title="O'chirish" data-bs-toggle="tooltip">
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
    .category-stats {
        display: inline-block;
        padding: 3px 8px;
        background: #e9ecef;
        border-radius: 12px;
        font-size: 12px;
        color: #495057;
        margin-left: 5px;
    }
    
    .drag-handle {
        cursor: move;
        color: #999;
        margin-right: 5px;
    }
    
    .drag-handle:hover {
        color: #667eea;
    }
    
    .sortable-ghost {
        opacity: 0.5;
        background: #e7f5ff;
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Drag and drop sorting
    <?php if (empty($search) && in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
    const sortableTable = document.getElementById('sortableCategories');
    if (sortableTable) {
        new Sortable(sortableTable, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                // Yangi tartibni saqlash
                const items = [];
                document.querySelectorAll('#sortableCategories tr').forEach((row, index) => {
                    const id = row.dataset.id;
                    if (id) {
                        items.push({
                            id: id,
                            tartib: index + 1
                        });
                    }
                });
                
                // Tartibni serverga yuborish
                fetch('/new-pos/categories/update-order', {
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
                        showSuccess('Tartib saqlandi');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }
    <?php endif; ?>
    
    // Tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltips.length > 0) {
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>