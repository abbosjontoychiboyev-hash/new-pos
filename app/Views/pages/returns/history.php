<!-- Page Title -->
<?php $title = 'Qaytarish tarixi'; ?>

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
    
    .filter-group input {
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
    
    .badge-return {
        background: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
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
        
        .table-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .table th, .table td {
            white-space: nowrap;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript (optional tooltips) -->
<?php ob_start(); ?>
<script>
    // Tooltips
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
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter"></i> Filtrlash
            </button>
        </div>
        <div class="filter-group">
            <a href="/new-pos/returns/history" class="btn btn-outline-secondary w-100">
                <i class="fas fa-redo"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Returns Table -->
<div class="table-card">
    <div class="table-header">
        <div class="table-title">
            <i class="fas fa-history"></i> Qaytarilgan savdolar
        </div>
        <span class="badge-return">Jami: <?= count($returns) ?> ta</span>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Chek raqami</th>
                    <th>Sana</th>
                    <th>Kassir</th>
                    <th>Mijoz</th>
                    <th>Umumiy summa</th>
                    <th>Holat</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($returns)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-undo-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bu davrda qaytarilgan savdolar yo'q</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($returns as $return): ?>
                    <tr>
                        <td><strong><?= $return['chek_raqami'] ?></strong></td>
                        <td><?= date('d.m.Y H:i', strtotime($return['sotilgan_vaqt'])) ?></td>
                        <td><?= htmlspecialchars($return['kassir_fio'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($return['mijoz_fio'] ?? 'Anonim') ?></td>
                        <td class="text-danger fw-bold"><?= number_format($return['umumiy_summa'], 0, ',', ' ') ?> so'm</td>
                        <td><span class="badge bg-danger">Bekor qilingan</span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>