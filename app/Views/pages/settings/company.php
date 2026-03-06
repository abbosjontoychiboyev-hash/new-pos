<!-- Page Title -->
<?php $title = 'Kompaniya sozlamalari'; ?>

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
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
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
    
    .logo-preview {
        width: 150px;
        height: 150px;
        border: 2px dashed #e0e0e0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        overflow: hidden;
    }
    
    .logo-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .logo-preview i {
        font-size: 50px;
        color: #e0e0e0;
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
        
        .row .col-md-3.text-center {
            margin-bottom: 20px;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Page Content -->
<div class="form-card">
    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-building"></i> Kompaniya ma'lumotlari
        </div>
        <a href="/new-pos/settings" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>
    
    <form method="POST" action="/new-pos/settings/company/save" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="row">
            <!-- Logo -->
            <div class="col-md-3 text-center mb-4">
                <label class="form-label">Kompaniya logosi</label>
                <div class="logo-preview">
                    <?php if (!empty($company['company_logo'])): ?>
                        <img src="/new-pos/<?= $company['company_logo'] ?>" alt="Logo">
                    <?php else: ?>
                        <i class="fas fa-image"></i>
                    <?php endif; ?>
                </div>
                <input type="file" name="company_logo" class="form-control" accept="image/*">
                <small class="text-muted">Rasm hajmi: max 2MB</small>
            </div>
            
            <div class="col-md-9">
                <div class="row">
                    <!-- Kompaniya nomi -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Kompaniya nomi</label>
                        <input type="text" name="company_name" class="form-control" 
                               value="<?= htmlspecialchars($company['company_name'] ?? 'POS Magazin') ?>">
                    </div>
                    
                    <!-- Manzil -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Manzil</label>
                        <textarea name="company_address" class="form-control" rows="2"><?= htmlspecialchars($company['company_address'] ?? '') ?></textarea>
                    </div>
                    
                    <!-- Telefon -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telefon raqam</label>
                        <input type="text" name="company_phone" class="form-control" 
                               value="<?= htmlspecialchars($company['company_phone'] ?? '') ?>">
                    </div>
                    
                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="company_email" class="form-control" 
                               value="<?= htmlspecialchars($company['company_email'] ?? '') ?>">
                    </div>
                    
                    <!-- STIR -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">STIR (INN)</label>
                        <input type="text" name="company_tax_number" class="form-control" 
                               value="<?= htmlspecialchars($company['company_tax_number'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="d-flex justify-content-end gap-2">
            <a href="/new-pos/settings" class="btn btn-cancel">
                <i class="fas fa-times"></i> Bekor qilish
            </a>
            <button type="submit" class="btn btn-save">
                <i class="fas fa-save"></i> Saqlash
            </button>
        </div>
    </form>
</div>