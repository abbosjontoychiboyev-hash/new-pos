<!-- Page Title -->
<?php $title = 'Subkategoriyani tahrirlash'; ?>

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
        border-radius: 10px;
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
        border-radius: 10px;
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
        border-radius: 10px;
        color: #666;
        transition: all 0.3s;
    }
    
    .btn-cancel:hover {
        background: #e0e0e0;
    }
    
    .category-preview {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .category-preview i {
        font-size: 24px;
    }
    
    .category-preview-text {
        font-size: 14px;
        line-height: 1.5;
    }
    
    .category-preview-text strong {
        font-size: 16px;
        display: block;
        margin-bottom: 3px;
    }
    
    .subcategory-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
    }
    
    .subcategory-info .label {
        color: #666;
        font-size: 13px;
        margin-bottom: 5px;
    }
    
    .subcategory-info .value {
        font-size: 18px;
        font-weight: 600;
    }
    
    .subcategory-info .value small {
        font-size: 14px;
        font-weight: normal;
        color: #999;
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
    
    .char-counter {
        float: right;
        font-size: 12px;
        color: #999;
    }
    
    .char-counter.text-danger {
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
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Show category preview when category is selected
    function showCategoryPreview(select) {
        const preview = document.getElementById('categoryPreview');
        const selectedOption = select.options[select.selectedIndex];
        
        if (select.value) {
            const categoryName = selectedOption.text;
            const categoryDesc = selectedOption.dataset.izoh || 'Tanlangan kategoriya';
            
            document.getElementById('selectedCategoryName').textContent = categoryName;
            document.getElementById('selectedCategoryDescription').textContent = categoryDesc;
        }
    }
    
    // Update character counter
    function updateCharCounter(elementId, maxLength) {
        const element = document.getElementById(elementId);
        const counter = document.getElementById(elementId + '-counter');
        const length = element.value.length;
        
        counter.textContent = length + '/' + maxLength;
        
        if (length > maxLength * 0.9) {
            counter.classList.add('text-danger');
        } else {
            counter.classList.remove('text-danger');
        }
    }
    
    // Form validation
    document.getElementById('subcategoryForm')?.addEventListener('submit', function(e) {
        const kategoriya = document.getElementById('kategoriya_id').value;
        const nomi = document.getElementById('nomi').value.trim();
        
        if (!kategoriya) {
            e.preventDefault();
            alert('Iltimos, kategoriya tanlang');
            return false;
        }
        
        if (nomi.length < 2) {
            e.preventDefault();
            alert('Subkategoriya nomi kamida 2 harfdan iborat bo\'lishi kerak');
            return false;
        }
        
        if (nomi.length > 120) {
            e.preventDefault();
            alert('Subkategoriya nomi 120 harfdan oshmasligi kerak');
            return false;
        }
        
        return true;
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize character counters
        updateCharCounter('nomi', 120);
        updateCharCounter('izoh', 255);
        
        // Show category preview with current selection
        const kategoriyaSelect = document.getElementById('kategoriya_id');
        if (kategoriyaSelect) {
            showCategoryPreview(kategoriyaSelect);
        }
    });
    
    // Confirm before leaving page with unsaved changes
    let formChanged = false;
    
    document.getElementById('subcategoryForm')?.addEventListener('input', function() {
        formChanged = true;
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    // Reset form changed flag on submit
    document.getElementById('subcategoryForm')?.addEventListener('submit', function() {
        formChanged = false;
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<div class="form-card">
    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-edit"></i> "<?= htmlspecialchars($subcategory['nomi']) ?>" subkategoriyasini tahrirlash
        </div>
        <a href="/new-pos/subcategories" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>
    
    <!-- Subcategory Info -->
    <div class="subcategory-info">
        <div class="row">
            <div class="col-md-4">
                <div class="label">Subkategoriya ID</div>
                <div class="value">#<?= $subcategory['id'] ?></div>
            </div>
            <div class="col-md-4">
                <div class="label">Yaratilgan vaqt</div>
                <div class="value">
                    <?= date('d.m.Y H:i', strtotime($subcategory['yaratilgan_vaqt'] ?? 'now')) ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="label">Oxirgi yangilanish</div>
                <div class="value">
                    <?= date('d.m.Y H:i', strtotime($subcategory['yangilangan_vaqt'] ?? 'now')) ?>
                </div>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="stats-grid">
            <?php
            // Mahsulotlar soni
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM mahsulotlar WHERE subkategoriya_id = ? AND ochirilgan_vaqt IS NULL");
            $stmt->execute([$subcategory['id']]);
            $prodCount = $stmt->fetch()['count'];
            ?>
            <div class="stat-item">
                <div class="stat-value"><?= $prodCount ?></div>
                <div class="stat-label">Mahsulotlar</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $subcategory['tartib'] ?? 0 ?></div>
                <div class="stat-label">Tartib raqami</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">
                    <?php if ($subcategory['faol']): ?>
                        <span class="text-success">Faol</span>
                    <?php else: ?>
                        <span class="text-danger">Faol emas</span>
                    <?php endif; ?>
                </div>
                <div class="stat-label">Holat</div>
            </div>
        </div>
    </div>
    
    <!-- Warning if has products -->
    <?php if ($prodCount > 0): ?>
    <div class="warning-box">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Diqqat!</strong> Bu subkategoriyada <?= $prodCount ?> ta mahsulot mavjud. Subkategoriya ma'lumotlarini o'zgartirsangiz, barcha mahsulotlar yangi ma'lumotlar bilan ko'rsatiladi.
    </div>
    <?php endif; ?>
    
    <!-- Category Preview -->
    <div class="category-preview" id="categoryPreview">
        <i class="fas fa-tag"></i>
        <div class="category-preview-text">
            <strong id="selectedCategoryName">
                <?php 
                // Kategoriya nomini olish
                $catStmt = $this->db->prepare("SELECT nomi FROM kategoriyalar WHERE id = ?");
                $catStmt->execute([$subcategory['kategoriya_id']]);
                $catName = $catStmt->fetch()['nomi'] ?? 'Noma\'lum';
                echo htmlspecialchars($catName);
                ?>
            </strong>
            <span id="selectedCategoryDescription">Joriy kategoriya</span>
        </div>
    </div>
    
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
    
    <form method="POST" action="/new-pos/subcategories/update/<?= $subcategory['id'] ?>" id="subcategoryForm">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="row">
            <!-- Kategoriya -->
            <div class="col-md-12 mb-3">
                <label for="kategoriya_id" class="form-label">
                    <i class="fas fa-tag"></i> Kategoriya <span class="text-danger">*</span>
                </label>
                <select class="form-select <?= isset($_SESSION['errors']['kategoriya_id']) ? 'is-invalid' : '' ?>" 
                        id="kategoriya_id" 
                        name="kategoriya_id" 
                        required
                        onchange="showCategoryPreview(this)">
                    <option value="">Kategoriya tanlang</option>
                    <?php foreach ($categories as $cat): ?>
                        <?php 
                        $selected = '';
                        if (isset($_SESSION['old']['kategoriya_id']) && $_SESSION['old']['kategoriya_id'] == $cat['id']) {
                            $selected = 'selected';
                        } elseif (!isset($_SESSION['old']['kategoriya_id']) && $subcategory['kategoriya_id'] == $cat['id']) {
                            $selected = 'selected';
                        }
                        ?>
                        <option value="<?= $cat['id'] ?>" <?= $selected ?> data-izoh="<?= htmlspecialchars($cat['izoh'] ?? '') ?>">
                            <?= htmlspecialchars($cat['nomi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($_SESSION['errors']['kategoriya_id'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= implode(', ', $_SESSION['errors']['kategoriya_id']) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Nomi -->
            <div class="col-md-12 mb-3">
                <label for="nomi" class="form-label">
                    <i class="fas fa-folder"></i> Subkategoriya nomi <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                    <input type="text" 
                           class="form-control <?= isset($_SESSION['errors']['nomi']) ? 'is-invalid' : '' ?>" 
                           id="nomi" 
                           name="nomi" 
                           value="<?= isset($_SESSION['old']['nomi']) ? htmlspecialchars($_SESSION['old']['nomi']) : htmlspecialchars($subcategory['nomi'] ?? '') ?>"
                           placeholder="Masalan: Gazlangan ichimliklar"
                           maxlength="120"
                           oninput="updateCharCounter('nomi', 120)"
                           required>
                    <small class="char-counter" id="nomi-counter">0/120</small>
                </div>
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
                <div class="position-relative">
                    <textarea class="form-control" 
                              id="izoh" 
                              name="izoh" 
                              rows="4" 
                              maxlength="255"
                              oninput="updateCharCounter('izoh', 255)"
                              placeholder="Subkategoriya haqida qisqacha ma'lumot..."><?= isset($_SESSION['old']['izoh']) ? htmlspecialchars($_SESSION['old']['izoh']) : htmlspecialchars($subcategory['izoh'] ?? '') ?></textarea>
                    <small class="char-counter" id="izoh-counter">0/255</small>
                </div>
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
                       value="<?= isset($_SESSION['old']['tartib']) ? $_SESSION['old']['tartib'] : ($subcategory['tartib'] ?? 0) ?>"
                       min="0">
                <small class="text-muted">Kichik raqamlar oldin ko'rsatiladi</small>
            </div>
            
            <!-- Faol -->
            <div class="col-md-6 mb-3">
                <div class="form-check mt-4">
                    <?php 
                    $faol = isset($_SESSION['old']['faol']) ? $_SESSION['old']['faol'] : ($subcategory['faol'] ?? 1);
                    ?>
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="faol" 
                           name="faol" 
                           value="1"
                           <?= $faol ? 'checked' : '' ?>>
                    <label class="form-check-label" for="faol">
                        <i class="fas fa-check-circle text-success"></i> Subkategoriya faol
                    </label>
                </div>
                <small class="text-muted">Faol bo'lmasa, mahsulot qo'shishda ko'rinmaydi</small>
            </div>
        </div>
        
        <!-- Eslatma -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Eslatma:</strong> Agar subkategoriya nomi yoki kategoriyasi o'zgartirilsa, bu subkategoriyaga tegishli barcha mahsulotlar yangi ma'lumotlar bilan ko'rsatiladi.
                </div>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="d-flex justify-content-end gap-2">
            <a href="/new-pos/subcategories" class="btn btn-cancel">
                <i class="fas fa-times"></i> Bekor qilish
            </a>
            <button type="submit" class="btn btn-save">
                <i class="fas fa-save"></i> Saqlash
            </button>
        </div>
    </form>
</div>

<?php 
// Clear old data
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>