<!-- Page Title -->
<?php $title = 'Top mahsulotlar hisoboti'; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .filter-form {
        display: flex;
        gap: 15px;
        align-items: flex-end;
        flex-wrap: wrap;
    }
    
    .filter-group {
        flex: 1;
        min-width: 150px;
    }
    
    .filter-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #555;
    }
    
    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
    }
    
    .table-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .table-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .table-title i {
        color: #667eea;
        margin-right: 10px;
    }
    
    .badge-rank {
        display: inline-block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        border-radius: 50%;
        background: #667eea;
        color: white;
        font-weight: 600;
    }
    
    .badge-rank.gold {
        background: #ffc107;
        color: #333;
    }
    
    .badge-rank.silver {
        background: #6c757d;
    }
    
    .badge-rank.bronze {
        background: #cd7f32;
    }
    
    .text-profit {
        color: #28a745;
        font-weight: 600;
    }
    
    .table th {
        background: #f8f9fa;
    }
    
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group {
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
<script>
    // Tooltips (ixtiyoriy)
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltips.length > 0) {
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<!-- Filter Form -->
<div class="filter-card">
    <form method="GET" class="filter-form">
        <div class="filter-group">
            <label>Boshlanish sanasi</label>
            <input type="date" name="start_date" value="<?= $startDate ?>">
        </div>
        <div class="filter-group">
            <label>Tugash sanasi</label>
            <input type="date" name="end_date" value="<?= $endDate ?>">
        </div>
        <div class="filter-group">
            <label>Ko'rsatish</label>
            <select name="limit">
                <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10 ta</option>
                <option value="20" <?= ($limit == 20) ? 'selected' : '' ?>>20 ta</option>
                <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50 ta</option>
                <option value="100" <?= ($limit == 100) ? 'selected' : '' ?>>100 ta</option>
            </select>
        </div>
        <div class="filter-group">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter"></i> Filtrlash
            </button>
        </div>
        <div class="filter-group">
            <a href="/new-pos/reports/top-products" class="btn btn-outline-secondary w-100">
                <i class="fas fa-redo"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Top Products Table -->
<div class="table-card">
    <div class="table-header">
        <div class="table-title">
            <i class="fas fa-chart-bar"></i> Top <?= $limit ?> mahsulot (<?= date('d.m.Y', strtotime($startDate)) ?> - <?= date('d.m.Y', strtotime($endDate)) ?>)
        </div>
        <span class="badge bg-info">Jami: <?= count($products) ?> ta</span>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mahsulot</th>
                    <th>Shtrix kod</th>
                    <th>Kategoriya</th>
                    <th>Sotilgan (dona)</th>
                    <th>Savdolar soni</th>
                    <th>Jami summa</th>
                    <th>Foyda</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu davrda mahsulot sotilmagan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $index => $product): ?>
                        <?php 
                        $rank = $index + 1;
                        $rankClass = '';
                        if ($rank == 1) $rankClass = 'gold';
                        elseif ($rank == 2) $rankClass = 'silver';
                        elseif ($rank == 3) $rankClass = 'bronze';
                        ?>
                        <tr>
                            <td>
                                <span class="badge-rank <?= $rankClass ?>"><?= $rank ?></span>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($product['nomi']) ?></strong>
                                <?php if (!empty($product['birlik'])): ?>
                                    <br><small class="text-muted"><?= $product['birlik'] ?></small>
                                <?php endif; ?>
                            </td>
                            <td><code><?= htmlspecialchars($product['shtrix_kod']) ?></code></td>
                            <td><?= htmlspecialchars($product['kategoriya'] ?? '-') ?></td>
                            <td><strong><?= number_format($product['jami_soni'], 0, ',', ' ') ?></strong></td>
                            <td><?= $product['savdolar_soni'] ?></td>
                            <td><strong><?= number_format($product['jami_summa'], 0, ',', ' ') ?> so'm</strong></td>
                            <td class="text-profit"><?= number_format($product['jami_foyda'], 0, ',', ' ') ?> so'm</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>