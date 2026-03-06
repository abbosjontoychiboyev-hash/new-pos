<!-- Page Title -->
<?php $title = 'Yangi yetkazib beruvchi qo\'shish'; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        max-width: 700px;
        margin: 0 auto;
    }
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Page Content -->
<div class="form-card">
    <div class="form-header">
        <h4 class="form-title"><i class="fas fa-truck"></i> Yetkazib beruvchi ma'lumotlari</h4>
        <a href="/new-pos/yetkazib" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>

    <form method="POST" action="/new-pos/yetkazib/store">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="mb-3">
            <label class="form-label">Nomi <span class="text-danger">*</span></label>
            <input type="text" name="nomi" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Telefon</label>
            <input type="text" name="telefon" class="form-control" placeholder="+998901234567">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Manzil</label>
            <input type="text" name="manzil" class="form-control">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Kelish kuni (qaysi kunlari mahsulot keltiradi)</label>
            <select name="kelish_kuni" class="form-select">
                <option value="">Tanlanmagan</option>
                <option value="Dushanba">Dushanba</option>
                <option value="Seshanba">Seshanba</option>
                <option value="Chorshanba">Chorshanba</option>
                <option value="Payshanba">Payshanba</option>
                <option value="Juma">Juma</option>
                <option value="Shanba">Shanba</option>
                <option value="Yakshanba">Yakshanba</option>
                <option value="Har kuni">Har kuni</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Izoh</label>
            <textarea name="izoh" class="form-control" rows="3"></textarea>
        </div>
        
        <hr>
        
        <div class="d-flex justify-content-end gap-2">
            <a href="/new-pos/yetkazib" class="btn btn-cancel">Bekor qilish</a>
            <button type="submit" class="btn btn-save">Saqlash</button>
        </div>
    </form>
</div>