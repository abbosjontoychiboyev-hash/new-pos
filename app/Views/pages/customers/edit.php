<!-- Page Title -->
<?php $title = 'Mijozni tahrirlash'; ?>

<!-- Page Content -->
<div class="form-card">
    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-user-edit"></i> "<?= htmlspecialchars($customer['fio']) ?>" ma'lumotlari
        </div>
        <a href="/new-pos/customers" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>
    
    <?php
    // Mijoz statistikasini olish
    $debtModel = new \App\Models\Debt();
    $debtInfo = $debtModel->getCustomerDebt($customer['id']);
    $totalDebt = $debtInfo['jami_qarz'] ?? 0;
    
    // Savdolar soni
    $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM savdolar WHERE mijoz_id = ? AND holat = 'YAKUNLANGAN'");
    $stmt->execute([$customer['id']]);
    $salesCount = $stmt->fetch()['count'];
    
    // Oxirgi savdo
    $stmt = $this->db->prepare("SELECT MAX(sotilgan_vaqt) as last FROM savdolar WHERE mijoz_id = ?");
    $stmt->execute([$customer['id']]);
    $lastSale = $stmt->fetch()['last'];
    ?>
    
    <!-- Customer Info -->
    <div class="customer-info">
        <div class="row">
            <div class="col-md-3">
                <div class="label">ID</div>
                <div class="value">#<?= $customer['id'] ?></div>
            </div>
            <div class="col-md-3">
                <div class="label">Qo'shilgan sana</div>
                <div class="value"><?= date('d.m.Y', strtotime($customer['yaratilgan_vaqt'])) ?></div>
            </div>
            <div class="col-md-3">
                <div class="label">Oxirgi yangilanish</div>
                <div class="value"><?= $customer['yangilangan_vaqt'] ? date('d.m.Y', strtotime($customer['yangilangan_vaqt'])) : '-' ?></div>
            </div>
            <div class="col-md-3">
                <div class="label">Holat</div>
                <div class="value">
                    <?php if ($customer['faol']): ?>
                        <span class="badge bg-success">Faol</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Faol emas</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value"><?= $salesCount ?></div>
                <div class="stat-label">Jami savdolar</div>
            </div>
            <div class="stat-item">
                <div class="stat-value <?= $totalDebt > 0 ? 'stat-debt' : '' ?>">
                    <?= number_format($totalDebt, 0, ',', ' ') ?> so'm
                </div>
                <div class="stat-label">Jami qarz</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $lastSale ? date('d.m.Y', strtotime($lastSale)) : '-' ?></div>
                <div class="stat-label">Oxirgi savdo</div>
            </div>
        </div>
    </div>
    
    <!-- Warning if has debt -->
    <?php if ($totalDebt > 0): ?>
    <div class="warning-box">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Diqqat!</strong> Bu mijozning <?= number_format($totalDebt, 0, ',', ' ') ?> so'm qarzi bor. Qarzni to'lov qismida ko'rishingiz mumkin.
        <a href="/new-pos/debt/customer/<?= $customer['id'] ?>" class="alert-link">Qarzni ko'rish →</a>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['flash']['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['flash']['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']['error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['flash']['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['flash']['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']['success']); ?>
    <?php endif; ?>
    
    <form method="POST" action="/new-pos/customers/update/<?= $customer['id'] ?>" id="customerForm">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="row">
            <!-- F.I.O. -->
            <div class="col-md-12 mb-3">
                <label for="fio" class="form-label">
                    <i class="fas fa-user"></i> F.I.O. <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       class="form-control <?= isset($_SESSION['errors']['fio']) ? 'is-invalid' : '' ?>" 
                       id="fio" 
                       name="fio" 
                       value="<?= isset($_SESSION['old']['fio']) ? htmlspecialchars($_SESSION['old']['fio']) : htmlspecialchars($customer['fio']) ?>"
                       required>
                <?php if (isset($_SESSION['errors']['fio'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= implode(', ', $_SESSION['errors']['fio']) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Telefon -->
            <div class="col-md-6 mb-3">
                <label for="telefon" class="form-label">
                    <i class="fas fa-phone"></i> Telefon raqam
                </label>
                <input type="text" 
                       class="form-control" 
                       id="telefon" 
                       name="telefon" 
                       value="<?= isset($_SESSION['old']['telefon']) ? htmlspecialchars($_SESSION['old']['telefon']) : htmlspecialchars($customer['telefon'] ?? '') ?>"
                       placeholder="+998901234567">
            </div>
            
            <!-- Manzil -->
            <div class="col-md-6 mb-3">
                <label for="manzil" class="form-label">
                    <i class="fas fa-map-marker-alt"></i> Manzil
                </label>
                <input type="text" 
                       class="form-control" 
                       id="manzil" 
                       name="manzil" 
                       value="<?= isset($_SESSION['old']['manzil']) ? htmlspecialchars($_SESSION['old']['manzil']) : htmlspecialchars($customer['manzil'] ?? '') ?>"
                       placeholder="Toshkent sh., Chilonzor t.">
            </div>
            
            <!-- Izoh -->
            <div class="col-md-12 mb-3">
                <label for="izoh" class="form-label">
                    <i class="fas fa-align-left"></i> Izoh
                </label>
                <textarea class="form-control" 
                          id="izoh" 
                          name="izoh" 
                          rows="3" 
                          placeholder="Mijoz haqida qo'shimcha ma'lumot..."><?= isset($_SESSION['old']['izoh']) ? htmlspecialchars($_SESSION['old']['izoh']) : htmlspecialchars($customer['izoh'] ?? '') ?></textarea>
            </div>
            
            <!-- Faol -->
            <div class="col-md-12 mb-3">
                <div class="form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="faol" 
                           name="faol" 
                           value="1"
                           <?= (isset($_SESSION['old']['faol']) ? $_SESSION['old']['faol'] : ($customer['faol'] ?? 1)) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="faol">
                        <i class="fas fa-check-circle text-success"></i> Mijoz faol
                    </label>
                </div>
                <small class="text-muted">Faol bo'lmasa, POS da mijoz tanlashda ko'rinmaydi</small>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="d-flex justify-content-end gap-2">
            <a href="/new-pos/customers" class="btn btn-cancel">
                <i class="fas fa-times"></i> Bekor qilish
            </a>
            <button type="submit" class="btn btn-save">
                <i class="fas fa-save"></i> Yangilash
            </button>
        </div>
    </form>
</div>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .form-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .form-title i {
        color: #667eea;
        margin-right: 10px;
    }
    
    .form-label {
        font-weight: 500;
        color: #555;
        margin-bottom: 8px;
    }
    
    .form-control, .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 15px;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
    }
    
    .invalid-feedback {
        font-size: 13px;
    }
    
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
    
    .btn-cancel {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        padding: 12px 30px;
        border-radius: 8px;
        color: #666;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-cancel:hover {
        background: #e0e0e0;
    }
    
    .customer-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
    }
    
    .customer-info .label {
        color: #666;
        font-size: 12px;
        margin-bottom: 5px;
    }
    
    .customer-info .value {
        font-weight: 600;
        color: #333;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-top: 15px;
    }
    
    .stat-item {
        background: white;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .stat-item .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #667eea;
    }
    
    .stat-item .stat-label {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }
    
    .stat-item .stat-debt {
        color: #dc3545;
    }
    
    .warning-box {
        background: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .warning-box i {
        color: #856404;
        margin-right: 10px;
    }
    
    .warning-box a {
        color: #856404;
        font-weight: 600;
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Form validation
    document.getElementById('customerForm')?.addEventListener('submit', function(e) {
        const fio = document.getElementById('fio').value.trim();
        
        if (fio.length < 3) {
            e.preventDefault();
            alert('F.I.O. kamida 3 harfdan iborat bo\'lishi kerak');
            return false;
        }
        
        if (fio.length > 160) {
            e.preventDefault();
            alert('F.I.O. 160 harfdan oshmasligi kerak');
            return false;
        }
        
        return true;
    });
    
    // Character counter for FIO
    document.getElementById('fio')?.addEventListener('input', function() {
        let length = this.value.length;
        let maxLength = 160;
        
        // Create or update counter
        let counter = document.getElementById('fio-counter');
        if (!counter) {
            counter = document.createElement('small');
            counter.id = 'fio-counter';
            counter.className = 'text-muted float-end';
            this.parentNode.appendChild(counter);
        }
        
        counter.textContent = length + '/' + maxLength;
        
        if (length > maxLength * 0.9) {
            counter.classList.add('text-danger');
        } else {
            counter.classList.remove('text-danger');
        }
    });
    
    // Trigger initial count
    if (document.getElementById('fio')) {
        document.getElementById('fio').dispatchEvent(new Event('input'));
    }
    
    // Confirm before leaving page with unsaved changes
    let formChanged = false;
    
    document.getElementById('customerForm')?.addEventListener('input', function() {
        formChanged = true;
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    // Reset form changed flag on submit
    document.getElementById('customerForm')?.addEventListener('submit', function() {
        formChanged = false;
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<?php 
// Clear old data
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>