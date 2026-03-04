<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategoriyalar - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; }
        .wrapper { display: flex; }
        
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
        }
        .sidebar-header { padding: 25px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-header h3 { font-size: 24px; font-weight: 700; margin: 0; color: white; }
        .sidebar-header p { font-size: 12px; opacity: 0.8; margin: 5px 0 0; }
        .nav-menu { padding: 20px 0; list-style: none; }
        .nav-item { margin: 5px 0; }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }
        .nav-link i { width: 25px; font-size: 16px; margin-right: 10px; text-align: center; }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }
        
        /* Top Bar */
        .top-bar {
            background: white;
            border-radius: 12px;
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-title h4 { font-size: 20px; font-weight: 600; color: #333; margin: 0; }
        .user-info { display: flex; align-items: center; gap: 10px; }
        .user-name { font-weight: 600; color: #333; }
        
        /* Content Card */
        .content-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        /* Filter Section */
        .filter-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
        }
        .search-box { flex: 1; display: flex; gap: 10px; }
        
        /* Table */
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
        
        .btn-action {
            padding: 5px 10px;
            margin: 0 2px;
            border-radius: 6px;
        }
        
        /* Category Card */
        .category-stats {
            display: inline-block;
            padding: 3px 8px;
            background: #e9ecef;
            border-radius: 12px;
            font-size: 12px;
            color: #495057;
            margin-left: 5px;
        }
        
        /* Pagination */
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
        
        /* Drag and Drop */
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
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>POS Magazin</h3>
                <p>Savdo boshqaruvi</p>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="/new-pos/dashboard" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/pos" class="nav-link">
                        <i class="fas fa-shopping-cart"></i> POS (Savdo)
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/products" class="nav-link">
                        <i class="fas fa-box"></i> Mahsulotlar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/categories" class="nav-link active">
                        <i class="fas fa-tags"></i> Kategoriyalar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/subcategories" class="nav-link">
                        <i class="fas fa-folder-open"></i> Subkategoriyalar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/customers" class="nav-link">
                        <i class="fas fa-users"></i> Mijozlar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/reports" class="nav-link">
                        <i class="fas fa-chart-bar"></i> Hisobotlar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/settings" class="nav-link">
                        <i class="fas fa-cog"></i> Sozlamalar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/logout" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Chiqish
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="page-title">
                    <h4>Kategoriyalar</h4>
                </div>
                <div class="user-info">
                    <div class="user-name"><?= $_SESSION['user']['fio'] ?? 'Foydalanuvchi' ?></div>
                    <div class="user-role badge bg-info"><?= $_SESSION['user']['rol_nomi'] ?? 'Role' ?></div>
                </div>
            </div>
            
            <!-- Content Card -->
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
                
                <!-- Flash Messages -->
                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['flash']['success'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['flash']['success']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['flash']['error'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['flash']['error']); ?>
                <?php endif; ?>
                
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
                                            <span class="drag-handle">
                                                <i class="fas fa-grip-vertical"></i>
                                            </span>
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
                                                <?= $category['products_count'] ?? $this->categoryModel->productsCount($category['id']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($category['faol']): ?>
                                                <span class="badge-status badge-success">Faol</span>
                                            <?php else: ?>
                                                <span class="badge-status badge-danger">Faol emas</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="/new-pos/subcategories?category=<?= $category['id'] ?>" 
                                               class="btn btn-sm btn-info btn-action" 
                                               title="Subkategoriyalar">
                                                <i class="fas fa-folder-open"></i>
                                            </a>
                                            
                                            <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
                                                <a href="/new-pos/categories/edit/<?= $category['id'] ?>" 
                                                   class="btn btn-sm btn-warning btn-action"
                                                   title="Tahrirlash">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <?php if ($_SESSION['user']['rol_nomi'] == 'Admin'): ?>
                                                    <form method="POST" 
                                                          action="/new-pos/categories/delete/<?= $category['id'] ?>" 
                                                          style="display: inline;" 
                                                          onsubmit="return confirm('Haqiqatan ham bu kategoriyani o\'chirmoqchimisiz? Bu kategoriyaga bog\'liq barcha ma\'lumotlar o\'chiriladi.');">
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
                                    <a class="page-link" href="?page=<?= $i ?><?= isset($search) && $search ? '&search=' . urlencode($search) : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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
                            const toast = document.createElement('div');
                            toast.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                            toast.style.zIndex = '9999';
                            toast.innerHTML = `
                                <i class="fas fa-check-circle me-2"></i> Tartib saqlandi
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                            document.body.appendChild(toast);
                            setTimeout(() => toast.remove(), 3000);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        }
        <?php endif; ?>
    </script>
</body>
</html>