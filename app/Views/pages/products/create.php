<!-- Page Title -->
<?php $title = 'Yangi mahsulot qo\'shish'; ?>

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
    
    .input-group-text {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-weight: 500;
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
    
    .barcode-preview {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 10px;
        font-family: monospace;
        font-size: 18px;
        letter-spacing: 2px;
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
    // Barcode generator
    function generateBarcode() {
        let barcode = '';
        for (let i = 0; i < 13; i++) {
            barcode += Math.floor(Math.random() * 10);
        }
        document.getElementById('shtrix_kod').value = barcode;
    }
    
    // Load subcategories via AJAX
    function loadSubcategories(categoryId) {
        if (!categoryId) {
            document.getElementById('subkategoriya_id').innerHTML = '<option value="">Subkategoriya tanlang</option>';
            return;
        }
        
        fetch('/new-pos/api/get-subcategories?category_id=' + categoryId)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Subkategoriya tanlang</option>';
                data.forEach(sub => {
                    options += `<option value="${sub.id}">${sub.nomi}</option>`;
                });
                document.getElementById('subkategoriya_id').innerHTML = options;
            })
            .catch(error => console.error('Xatolik:', error));
    }
    
    // Calculate profit
    function calculateProfit() {
        let kelish = parseFloat(document.getElementById('kelish_narxi').value.replace(/[^0-9\.]/g, '')) || 0;
        let sotish = parseFloat(document.getElementById('sotish_narxi').value.replace(/[^0-9\.]/g, '')) || 0;
        
        if (kelish > 0 && sotish > 0) {
            let foyda = sotish - kelish;
            let foiz = (foyda / kelish * 100).toFixed(1);
            
            let display = document.getElementById('foyda_display');
            display.innerHTML = `${foyda.toLocaleString()} so'm (${foiz}%)`;
            
            if (foyda < 0) {
                display.classList.add('text-danger');
            } else {
                display.classList.remove('text-danger');
            }
        } else {
            document.getElementById('foyda_display').innerHTML = '0 so\'m (0%)';
        }
    }
    
    // Form submit validation
    document.getElementById('productForm')?.addEventListener('submit', function(e) {
        let kelish = parseFloat(document.getElementById('kelish_narxi').value.replace(/[^0-9\.]/g, '')) || 0;
        let sotish = parseFloat(document.getElementById('sotish_narxi').value.replace(/[^0-9\.]/g, '')) || 0;
        
        if (sotish < kelish) {
            if (!confirm('Sotish narxi kelish narxidan past. Davom etishni xohlaysizmi?')) {
                e.preventDefault();
            }
        }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate profit on load
        calculateProfit();
        
        // Add event listeners
        document.getElementById('kelish_narxi')?.addEventListener('keyup', calculateProfit);
        document.getElementById('sotish_narxi')?.addEventListener('keyup', calculateProfit);
        
        // Load subcategories if category already selected
        <?php if (isset($_SESSION['old']['kategoriya_id']) && $_SESSION['old']['kategoriya_id']): ?>
            loadSubcategories(<?= $_SESSION['old']['kategoriya_id'] ?>);
        <?php endif; ?>

        // Adjust quantity step based on unit selection
        function updateQuantityStep() {
            const unit = document.getElementById('birlik')?.value || '';
            const step = (unit.toLowerCase() === 'kg' || unit.toLowerCase() === 'litr') ? '0.001' : '1';
            document.getElementById('miqdor').step = step;
            document.getElementById('minimal_miqdor').step = step;
        }

        document.getElementById('birlik')?.addEventListener('change', updateQuantityStep);
        updateQuantityStep();
        
        // Character counter for product name
        const nomiInput = document.getElementById('nomi');
        if (nomiInput) {
            nomiInput.addEventListener('input', function() {
                let length = this.value.length;
                let maxLength = 160;
                
                let counter = document.getElementById('nomi-counter');
                if (!counter) {
                    counter = document.createElement('small');
                    counter.id = 'nomi-counter';
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
            
            nomiInput.dispatchEvent(new Event('input'));
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<div class="form-card">
    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-box"></i> Mahsulot ma'lumotlari
        </div>
        <a href="/new-pos/products" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>
    
    <?php if (isset($_SESSION['flash']['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['flash']['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']['error']); ?>
    <?php endif; ?>
    
    <form method="POST" action="/new-pos/products/store" id="productForm">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="row">
            <!-- Shtrix kod -->
            <div class="col-md-6 mb-3">
                <label for="shtrix_kod" class="form-label">
                    <i class="fas fa-barcode"></i> Shtrix kod <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control <?= isset($_SESSION['errors']['shtrix_kod']) ? 'is-invalid' : '' ?>" 
                           id="shtrix_kod" 
                           name="shtrix_kod" 
                           value="<?= isset($_SESSION['old']['shtrix_kod']) ? htmlspecialchars($_SESSION['old']['shtrix_kod']) : '' ?>"
                           placeholder="Masalan: 4780012345678"
                           maxlength="13"
                           required>
                    <button type="button" class="btn btn-outline-secondary" onclick="generateBarcode()">
                        <i class="fas fa-random"></i>
                    </button>
                </div>
                <?php if (isset($_SESSION['errors']['shtrix_kod'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= implode(', ', $_SESSION['errors']['shtrix_kod']) ?>
                    </div>
                <?php endif; ?>
                <small class="text-muted">13 xonali raqam</small>
            </div>
            
            <!-- Nomi -->
            <div class="col-md-6 mb-3">
                <label for="nomi" class="form-label">
                    <i class="fas fa-tag"></i> Mahsulot nomi <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       class="form-control <?= isset($_SESSION['errors']['nomi']) ? 'is-invalid' : '' ?>" 
                       id="nomi" 
                       name="nomi" 
                       value="<?= isset($_SESSION['old']['nomi']) ? htmlspecialchars($_SESSION['old']['nomi']) : '' ?>"
                       placeholder="Masalan: Coca Cola 1L"
                       maxlength="160"
                       required>
                <?php if (isset($_SESSION['errors']['nomi'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= implode(', ', $_SESSION['errors']['nomi']) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Kategoriya -->
            <div class="col-md-6 mb-3">
                <label for="kategoriya_id" class="form-label">
                    <i class="fas fa-folder"></i> Kategoriya <span class="text-danger">*</span>
                </label>
                <select class="form-select <?= isset($_SESSION['errors']['kategoriya_id']) ? 'is-invalid' : '' ?>" 
                        id="kategoriya_id" 
                        name="kategoriya_id" 
                        required
                        onchange="loadSubcategories(this.value)">
                    <option value="">Kategoriya tanlang</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" 
                            <?= (isset($_SESSION['old']['kategoriya_id']) && $_SESSION['old']['kategoriya_id'] == $category['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['nomi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($_SESSION['errors']['kategoriya_id'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= implode(', ', $_SESSION['errors']['kategoriya_id']) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Subkategoriya -->
            <div class="col-md-6 mb-3">
                <label for="subkategoriya_id" class="form-label">
                    <i class="fas fa-folder-open"></i> Subkategoriya
                </label>
                <select class="form-select" id="subkategoriya_id" name="subkategoriya_id">
                    <option value="">Subkategoriya tanlang</option>
                </select>
            </div>
            
            <!-- Birlik -->
            <div class="col-md-6 mb-3">
                <label for="birlik" class="form-label">
                    <i class="fas fa-balance-scale"></i> Birlik <span class="text-danger">*</span>
                </label>
                <select class="form-select <?= isset($_SESSION['errors']['birlik']) ? 'is-invalid' : '' ?>" 
                        id="birlik" 
                        name="birlik" 
                        required>
                    <option value="dona" <?= (isset($_SESSION['old']['birlik']) && $_SESSION['old']['birlik'] == 'dona') ? 'selected' : '' ?>>Dona</option>
                    <option value="kg" <?= (isset($_SESSION['old']['birlik']) && $_SESSION['old']['birlik'] == 'kg') ? 'selected' : '' ?>>Kilogramm (kg)</option>
                    <option value="litr" <?= (isset($_SESSION['old']['birlik']) && $_SESSION['old']['birlik'] == 'litr') ? 'selected' : '' ?>>Litr</option>
                    <option value="quti" <?= (isset($_SESSION['old']['birlik']) && $_SESSION['old']['birlik'] == 'quti') ? 'selected' : '' ?>>Quti</option>
                    <option value="paket" <?= (isset($_SESSION['old']['birlik']) && $_SESSION['old']['birlik'] == 'paket') ? 'selected' : '' ?>>Paket</option>
                </select>
                <?php if (isset($_SESSION['errors']['birlik'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= implode(', ', $_SESSION['errors']['birlik']) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Kelish narxi -->
            <div class="col-md-6 mb-3">
                <label for="kelish_narxi" class="form-label">
                    <i class="fas fa-arrow-down"></i> Kelish narxi <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control <?= isset($_SESSION['errors']['kelish_narxi']) ? 'is-invalid' : '' ?>" 
                           id="kelish_narxi" 
                           name="kelish_narxi" 
                           value="<?= isset($_SESSION['old']['kelish_narxi']) ? $_SESSION['old']['kelish_narxi'] : '' ?>"
                           placeholder="0"
                           onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')"
                           required>
                    <span class="input-group-text">so'm</span>
                </div>
                <?php if (isset($_SESSION['errors']['kelish_narxi'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= implode(', ', $_SESSION['errors']['kelish_narxi']) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sotish narxi -->
            <div class="col-md-6 mb-3">
                <label for="sotish_narxi" class="form-label">
                    <i class="fas fa-arrow-up"></i> Sotish narxi <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="text" 
                           class="form-control <?= isset($_SESSION['errors']['sotish_narxi']) ? 'is-invalid' : '' ?>" 
                           id="sotish_narxi" 
                           name="sotish_narxi" 
                           value="<?= isset($_SESSION['old']['sotish_narxi']) ? $_SESSION['old']['sotish_narxi'] : '' ?>"
                           placeholder="0"
                           onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')"
                           required>
                    <span class="input-group-text">so'm</span>
                </div>
                <?php if (isset($_SESSION['errors']['sotish_narxi'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= implode(', ', $_SESSION['errors']['sotish_narxi']) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Foyda ko'rsatkichi -->
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    <i class="fas fa-chart-line"></i> Foyda
                </label>
                <div class="form-control bg-light" id="foyda_display">
                    0 so'm (0%)
                </div>
            </div>
            
            <!-- Miqdor -->
            <div class="col-md-6 mb-3">
                <label for="miqdor" class="form-label">
                    <i class="fas fa-cubes"></i> Boshlang'ich miqdor
                </label>
                <input type="number" 
                       class="form-control" 
                       id="miqdor" 
                       name="miqdor" 
                       value="<?= isset($_SESSION['old']['miqdor']) ? $_SESSION['old']['miqdor'] : '0' ?>"
                       min="0">
                <small class="text-muted">0 bo'lsa, keyinroq qo'shishingiz mumkin</small>
            </div>
            
            <!-- Minimal miqdor -->
            <div class="col-md-6 mb-3">
                <label for="minimal_miqdor" class="form-label">
                    <i class="fas fa-exclamation-triangle"></i> Minimal miqdor
                </label>
                <input type="number" 
                       class="form-control" 
                       id="minimal_miqdor" 
                       name="minimal_miqdor" 
                       value="<?= isset($_SESSION['old']['minimal_miqdor']) ? $_SESSION['old']['minimal_miqdor'] : '5' ?>"
                       min="0">
                <small class="text-muted">Bu miqdordan kam bo'lsa, ogohlantirish chiqadi</small>
            </div>
            
            <!-- Faol -->
            <div class="col-md-6 mb-3">
                <div class="form-check mt-4">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="faol" 
                           name="faol" 
                           value="1"
                           <?= !isset($_SESSION['old']['faol']) || $_SESSION['old']['faol'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="faol">
                        <i class="fas fa-check-circle text-success"></i> Mahsulot faol
                    </label>
                </div>
                <small class="text-muted">Faol bo'lmasa, savdoda ko'rinmaydi</small>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="d-flex justify-content-end gap-2">
            <a href="/new-pos/products" class="btn btn-cancel">
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