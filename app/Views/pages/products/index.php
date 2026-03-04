<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahsulotlar - POS System</title>
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
        .filter-box { width: 200px; }
        
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
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #e7f5ff; color: #004085; }
        
        .btn-action {
            padding: 5px 10px;
            margin: 0 2px;
            border-radius: 6px;
        }
        
        /* Stock indicator */
        .stock-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .stock-high { background: #28a745; }
        .stock-medium { background: #ffc107; }
        .stock-low { background: #dc3545; }
        
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
                    <a href="/new-pos/products" class="nav-link active">
                        <i class="fas fa-box"></i> Mahsulotlar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/new-pos/categories" class="nav-link">
                        <i class="fas fa-tags"></i> Kategoriyalar
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
                    <h4>Mahsulotlar</h4>
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
                               placeholder="Qidirish (nomi yoki shtrix kod)" 
                               value="<?= htmlspecialchars($search ?? '') ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Qidirish
                        </button>
                    </form>
                    
                    <div class="filter-box">
                        <select name="category" class="form-control" onchange="window.location.href=this.value">
                            <option value="/new-pos/products">Barcha kategoriyalar</option>
                            <?php foreach ($categories as $cat): ?>
                                <?php 
                                $selected = (isset($selectedCategory) && $selectedCategory == $cat['id']) ? 'selected' : '';
                                $url = '/new-pos/products' . ($cat['id'] ? '?category=' . $cat['id'] : '');
                                ?>
                                <option value="<?= $url ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($cat['nomi']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
                    <a href="/new-pos/products/create" class="btn btn-success">
                        <i class="fas fa-plus"></i> Yangi mahsulot
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
                
                <!-- Products Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Shtrix kod</th>
                                <th>Nomi</th>
                                <th>Kategoriya</th>
                                <th>Kelish narxi</th>
                                <th>Sotish narxi</th>
                                <th>Miqdor</th>
                                <th>Holat</th>
                                <th>Amallar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Hech qanday mahsulot topilmadi</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <?php 
                                    $stockStatus = 'high';
                                    $stockClass = 'stock-high';
                                    if ($product['miqdor'] <= 0) {
                                        $stockStatus = 'low';
                                        $stockClass = 'stock-low';
                                    } elseif ($product['miqdor'] <= ($product['minimal_miqdor'] ?? 5)) {
                                        $stockStatus = 'medium';
                                        $stockClass = 'stock-medium';
                                    }
                                    ?>
                                    <tr>
                                        <td><span class="badge bg-secondary">#<?= $product['id'] ?></span></td>
                                        <td><code><?= htmlspecialchars($product['shtrix_kod']) ?></code></td>
                                        <td>
                                            <strong><?= htmlspecialchars($product['nomi']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= $product['birlik'] ?? 'dona' ?></small>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($product['kategoriya_nomi'] ?? '-') ?>
                                            <?php if (!empty($product['subkategoriya_nomi'])): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($product['subkategoriya_nomi']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= number_format($product['kelish_narxi'] ?? 0, 0, ',', ' ') ?> so'm</td>
                                        <td><strong class="text-primary"><?= number_format($product['sotish_narxi'] ?? 0, 0, ',', ' ') ?> so'm</strong></td>
                                        <td>
                                            <span class="stock-indicator <?= $stockClass ?>"></span>
                                            <span class="fw-bold <?= $stockStatus == 'low' ? 'text-danger' : ($stockStatus == 'medium' ? 'text-warning' : 'text-success') ?>">
                                                <?= $product['miqdor'] ?? 0 ?>
                                            </span>
                                            <br>
                                            <small class="text-muted">Min: <?= $product['minimal_miqdor'] ?? 5 ?></small>
                                        </td>
                                        <td>
                                            <?php if ($product['faol'] ?? 1): ?>
                                                <span class="badge-status badge-success">Faol</span>
                                            <?php else: ?>
                                                <span class="badge-status badge-danger">Faol emas</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info btn-action" onclick="viewProduct(<?= $product['id'] ?>)" data-bs-toggle="modal" data-bs-target="#productModal">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
                                                <a href="/new-pos/products/edit/<?= $product['id'] ?>" class="btn btn-sm btn-warning btn-action">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <button type="button" class="btn btn-sm btn-success btn-action" onclick="showStockModal(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['nomi'])) ?>', <?= $product['miqdor'] ?? 0 ?>)">
                                                    <i class="fas fa-boxes"></i>
                                                </button>
                                                
                                                <?php if (($_SESSION['user']['rol_nomi'] ?? '') == 'Admin'): ?>
                                                    <form method="POST" action="/new-pos/products/delete/<?= $product['id'] ?>" style="display: inline;" onsubmit="return confirm('Haqiqatan ham bu mahsulotni o\'chirmoqchimisiz?');">
                                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger btn-action">
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
        </div>
    </div>
    
    <!-- Product Details Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mahsulot ma'lumotlari</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="productModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Yuklanmoqda...</span>
                        </div>
                        <p class="mt-2">Ma'lumotlar yuklanmoqda...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="stockModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mahsulot miqdorini tuzatish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/new-pos/products/adjust-stock">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <div class="modal-body">
                        <input type="hidden" name="product_id" id="stock_product_id">
                        
                        <div class="mb-3">
                            <label class="form-label">Mahsulot</label>
                            <input type="text" class="form-control" id="stock_product_name" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Joriy miqdor</label>
                            <input type="text" class="form-control" id="stock_current" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_quantity" class="form-label">Yangi miqdor <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="new_quantity" name="new_quantity" min="0" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="reason" class="form-label">Sabab <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reason" name="reason" rows="2" required placeholder="Masalan: Inventarizatsiya, yangi kirim, hisobdan chiqarish..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                        <button type="submit" class="btn btn-primary">Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Base URL
        const baseUrl = '/new-pos';
        
        // View product details
        function viewProduct(id) {
            fetch(baseUrl + '/api/products/' + id)
                .then(response => response.json())
                .then(data => {
                    let html = `
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">ID</th>
                                            <td>#${data.product.id}</td>
                                        </tr>
                                        <tr>
                                            <th>Shtrix kod</th>
                                            <td><code>${data.product.shtrix_kod}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Nomi</th>
                                            <td><strong>${data.product.nomi}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Kategoriya</th>
                                            <td>${data.category ? data.category.nomi : '-'}</td>
                                        </tr>
                                        <tr>
                                            <th>Birlik</th>
                                            <td>${data.product.birlik || 'dona'}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Kelish narxi</th>
                                            <td>${formatMoney(data.product.kelish_narxi)}</td>
                                        </tr>
                                        <tr>
                                            <th>Sotish narxi</th>
                                            <td><strong class="text-primary">${formatMoney(data.product.sotish_narxi)}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Joriy miqdor</th>
                                            <td>
                                                <span class="fw-bold ${data.product.miqdor <= data.product.minimal_miqdor ? 'text-danger' : 'text-success'}">
                                                    ${data.product.miqdor}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Minimal miqdor</th>
                                            <td>${data.product.minimal_miqdor}</td>
                                        </tr>
                                        <tr>
                                            <th>Holat</th>
                                            <td>${data.product.faol ? '<span class="badge bg-success">Faol</span>' : '<span class="badge bg-danger">Faol emas</span>'}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            ${data.stats ? `
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Statistika</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Jami sotuvlar</th>
                                            <td>${data.stats.jami_sotuvlar || 0}</td>
                                            <th>Jami sotilgan</th>
                                            <td>${data.stats.jami_sotilgan || 0} ${data.product.birlik || 'dona'}</td>
                                        </tr>
                                        <tr>
                                            <th>Jami tushum</th>
                                            <td>${formatMoney(data.stats.jami_tushum || 0)}</td>
                                            <th>Oxirgi sotilgan</th>
                                            <td>${data.stats.oxirgi_sotilgan ? new Date(data.stats.oxirgi_sotilgan).toLocaleString('uz-UZ') : '-'}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            ` : ''}
                            
                            ${data.history && data.history.length > 0 ? `
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Oxirgi harakatlar</h6>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Vaqt</th>
                                                <th>Amal</th>
                                                <th>O'zgarish</th>
                                                <th>Izoh</th>
                                                <th>Foydalanuvchi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${data.history.map(item => `
                                                <tr>
                                                    <td>${new Date(item.yaratilgan_vaqt).toLocaleString('uz-UZ')}</td>
                                                    <td>${item.amal}</td>
                                                    <td class="${item.miqdor_ozgarish > 0 ? 'text-success' : 'text-danger'}">
                                                        ${item.miqdor_ozgarish > 0 ? '+' : ''}${item.miqdor_ozgarish}
                                                    </td>
                                                    <td>${item.izoh || '-'}</td>
                                                    <td>${item.foydalanuvchi_fio || '-'}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    `;
                    document.getElementById('productModalBody').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('productModalBody').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> Xatolik yuz berdi: ${error}
                        </div>
                    `;
                });
        }
        
        // Show stock adjustment modal
        function showStockModal(id, name, currentStock) {
            document.getElementById('stock_product_id').value = id;
            document.getElementById('stock_product_name').value = name;
            document.getElementById('stock_current').value = currentStock;
            
            let modal = new bootstrap.Modal(document.getElementById('stockModal'));
            modal.show();
        }
        
        // Format money
        function formatMoney(amount) {
            return new Intl.NumberFormat('uz-UZ', { 
                style: 'currency', 
                currency: 'UZS',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount).replace('UZS', '').trim() + ' so\'m';
        }
        
        // Auto close alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>