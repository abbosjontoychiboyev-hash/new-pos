<!-- Page Title -->
<?php $title = 'Kategoriyani tahrirlash'; ?>

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
        font-weight: 600;
        border-radius: 8px;
        color: white;
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
        font-weight: 600;
        border-radius: 8px;
        color: #666;
        transition: all 0.3s;
    }
    
    .btn-cancel:hover {
        background: #e0e0e0;
    }
    
    /* Category Info */
    .category-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .category-info .label {
        color: #666;
        font-size: 12px;
        margin-bottom: 3px;
    }
    
    .category-info .value {
        font-weight: 600;
        color: #333;
    }
    
    @media (max-width: 768px) {
        .form-card {
            padding: 20px;
        }
        
        .form-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
        
        .btn-outline-secondary {
            width: 100%;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Nomi maydoni uchun character counter
        const nomiInput = document.getElementById('nomi');
        if (nomiInput) {
            const counter = document.createElement('small');
            counter.id = 'nomi-counter';
            counter.className = 'text-muted float-end';
            nomiInput.parentNode.appendChild(counter);
            
            nomiInput.addEventListener('input', function() {
                const len = this.value.length;
                const max = this.getAttribute('maxlength') || 120;
                counter.textContent = len + '/' + max;
                if (len > max * 0.9) {
                    counter.classList.add('text-danger');
                } else {
                    counter.classList.remove('text-danger');
                }
            });
            
            nomiInput.dispatchEvent(new Event('input'));
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<div class="form-card">
    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-edit"></i> "<?= htmlspecialchars($category['nomi']) ?>" kategoriyasini tahrirlash
        </div>
        <a href="/new-pos/categories" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>
    
    <!-- Kategoriya ma'lumotlari (qisqacha) -->
    <div class="category-info">
        <div class="row">
            <div class="col-md-4">
                <div class="label">ID</div>
                <div class="value">#<?= $category['id'] ?></div>
            </div>
            <div class="col-md-4">
                <div class="label">Yaratilgan</div>
                <div class="value"><?= date('d.m.Y', strtotime($category['yaratilgan_vaqt'] ?? 'now')) ?></div>
            </div>
            <div class="col-md-4">
                <div class="label">Oxirgi yangilanish</div>
                <div class="value"><?= isset($category['yangilangan_vaqt']) ? date('d.m.Y H:i', strtotime($category['yangilangan_vaqt'])) : '-' ?></div>
            </div>
        </div>
    </div>
    
    <!-- Xatolik xabarlari -->
    <?php if (isset($_SESSION['flash']['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['flash']['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']['error']); ?>
    <?php endif; ?>
    
    <!-- Muvaffaqiyat xabari (agar kerak bo'lsa) -->
    <?php if (isset($_SESSION['flash']['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['flash']['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']['success']); ?>
    <?php endif; ?>
    
    <form method="POST" action="/new-pos/categories/update/<?= $category['id'] ?>" id="categoryForm">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="row">
            <!-- Kategoriya nomi -->
            <div class="col-md-12 mb-3">
                <label for="nomi" class="form-label">
                    <i class="fas fa-tag"></i> Kategoriya nomi <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       class="form-control <?= isset($_SESSION['errors']['nomi']) ? 'is-invalid' : '' ?>" 
                       id="nomi" 
                       name="nomi" 
                       value="<?= isset($_SESSION['old']['nomi']) ? htmlspecialchars($_SESSION['old']['nomi']) : htmlspecialchars($category['nomi']) ?>"
                       maxlength="120"
                       required>
                <?php if (isset($_SESSION['errors']['nomi'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= implode(', ', $_SESSION['errors']['nomi']) ?>
                    </div>
                <?php endif; ?>
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
                          maxlength="255"><?= isset($_SESSION['old']['izoh']) ? htmlspecialchars($_SESSION['old']['izoh']) : htmlspecialchars($category['izoh'] ?? '') ?></textarea>
            </div>
            
            <!-- Tartib raqami -->
            <div class="col-md-6 mb-3">
                <label for="tartib" class="form-label">
                    <i class="fas fa-sort-numeric-down"></i> Tartib raqami
                </label>
                <input type="number" 
                       class="form-control" 
                       id="tartib" 
                       name="tartib" 
                       value="<?= isset($_SESSION['old']['tartib']) ? $_SESSION['old']['tartib'] : ($category['tartib'] ?? 0) ?>"
                       min="0">
                <small class="text-muted">Kichik raqamlar oldin ko'rsatiladi</small>
            </div>
            
            <!-- Faol -->
            <div class="col-md-6 mb-3">
                <div class="form-check mt-4">
                    <?php 
                    $faol = isset($_SESSION['old']['faol']) ? $_SESSION['old']['faol'] : ($category['faol'] ?? 1);
                    ?>
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="faol" 
                           name="faol" 
                           value="1"
                           <?= $faol ? 'checked' : '' ?>>
                    <label class="form-check-label" for="faol">
                        <i class="fas fa-check-circle text-success"></i> Kategoriya faol
                    </label>
                </div>
                <small class="text-muted">Faol bo'lmasa, mahsulot qo'shishda ko'rinmaydi</small>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="d-flex justify-content-end gap-2">
            <a href="/new-pos/categories" class="btn btn-cancel">
                <i class="fas fa-times"></i> Bekor qilish
            </a>
            <button type="submit" class="btn btn-save">
                <i class="fas fa-save"></i> Yangilash
            </button>
        </div>
    </form>
</div>

<?php 
// Clear old data
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>