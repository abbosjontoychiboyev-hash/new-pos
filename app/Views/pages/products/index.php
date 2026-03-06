<!-- Page Title -->
<?php $title = 'Mahsulotlar'; ?>

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
        width: 200px;
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
    .badge-warning { background: #fff3cd; color: #856404; }
    .badge-danger { background: #f8d7da; color: #721c24; }
    .badge-info { background: #e7f5ff; color: #004085; }
    
    .btn-action {
        padding: 5px 10px;
        margin: 0 2px;
        border-radius: 6px;
    }
    
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
    
    .modal-content {
        border-radius: 12px;
        border: none;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0;
    }
    
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
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
        
        .btn-action {
            padding: 3px 6px;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Base URL
    const baseUrl = '/new-pos';
    
    // View product details
    function viewProduct(id) {
        const modalBody = document.getElementById('productModalBody');
        modalBody.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Yuklanmoqda...</span>
                </div>
                <p class="mt-2">Ma'lumotlar yuklanmoqda...</p>
            </div>
        `;
        
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
                                <h6 class="fw-bold mb-3">Statistika</h6>
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
                                <h6 class="fw-bold mb-3">Oxirgi harakatlar</h6>
                                <div class="table-responsive">
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
                                                    <td class="${item.miqdor_ozgarish > 0 ? 'text-success' : 'text-danger'} fw-bold">
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
                        </div>
                        ` : ''}
                    </div>
                `;
                document.getElementById('productModalBody').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('productModalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i> Xatolik yuz berdi: ${error}
                    </div>
                `;
            });
    }
    
    // Show stock adjustment modal
    function showStockModal(id, name, currentStock) {
        document.getElementById('stock_product_id').value = id;
        document.getElementById('stock_product_name').value = name;
        document.getElementById('stock_current').value = currentStock;
        document.getElementById('new_quantity').value = currentStock;
        
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
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltips.length > 0) {
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        }
    });
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
                   placeholder="Qidirish (nomi yoki shtrix kod)" 
                   value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Qidirish
            </button>
            <?php if (!empty($search) || !empty($selectedCategory)): ?>
                <a href="/new-pos/products" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Tozalash
                </a>
            <?php endif; ?>
        </form>
        
        <div class="filter-box">
            <select name="category" class="form-select" onchange="window.location.href=this.value">
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
                                <button type="button" class="btn btn-sm btn-info btn-action" onclick="viewProduct(<?= $product['id'] ?>)" data-bs-toggle="modal" data-bs-target="#productModal" title="Ko'rish" data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <?php if (in_array($_SESSION['user']['rol_nomi'] ?? '', ['Admin', 'Omborchi'])): ?>
                                    <a href="/new-pos/products/edit/<?= $product['id'] ?>" class="btn btn-sm btn-warning btn-action" title="Tahrirlash" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button type="button" class="btn btn-sm btn-success btn-action" onclick="showStockModal(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['nomi'])) ?>', <?= $product['miqdor'] ?? 0 ?>)" title="Miqdorni tuzatish" data-bs-toggle="tooltip">
                                        <i class="fas fa-boxes"></i>
                                    </button>
                                    
                                    <?php if (($_SESSION['user']['rol_nomi'] ?? '') == 'Admin'): ?>
                                        <form method="POST" action="/new-pos/products/delete/<?= $product['id'] ?>" style="display: inline;" onsubmit="return confirm('Haqiqatan ham bu mahsulotni o\'chirmoqchimisiz?');">
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
                        <a class="page-link" href="?page=<?= $i ?><?= isset($search) && $search ? '&search=' . urlencode($search) : '' ?><?= isset($selectedCategory) && $selectedCategory ? '&category=' . $selectedCategory : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
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

<?php 
// Clear old data
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>