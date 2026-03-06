<!-- Page Title -->
<?php $title = 'Yetkazib beruvchini tahrirlash'; ?>

<!-- Extra CSS (create.php dagi bilan bir xil) -->
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
        <h4 class="form-title"><i class="fas fa-edit"></i> "<?= htmlspecialchars($yetkazib['nomi']) ?>" tahrirlash</h4>
        <a href="/new-pos/yetkazib" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>

    <form method="POST" action="/new-pos/yetkazib/update/<?= $yetkazib['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <div class="mb-3">
            <label class="form-label">Nomi <span class="text-danger">*</span></label>
            <input type="text" name="nomi" class="form-control" value="<?= htmlspecialchars($yetkazib['nomi']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Telefon</label>
            <input type="text" name="telefon" class="form-control" value="<?= htmlspecialchars($yetkazib['telefon'] ?? '') ?>">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Manzil</label>
            <input type="text" name="manzil" class="form-control" value="<?= htmlspecialchars($yetkazib['manzil'] ?? '') ?>">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Kelish kuni</label>
            <select name="kelish_kuni" class="form-select">
                <option value="">Tanlanmagan</option>
                <option value="Dushanba" <?= ($yetkazib['kelish_kuni'] == 'Dushanba') ? 'selected' : '' ?>>Dushanba</option>
                <option value="Seshanba" <?= ($yetkazib['kelish_kuni'] == 'Seshanba') ? 'selected' : '' ?>>Seshanba</option>
                <option value="Chorshanba" <?= ($yetkazib['kelish_kuni'] == 'Chorshanba') ? 'selected' : '' ?>>Chorshanba</option>
                <option value="Payshanba" <?= ($yetkazib['kelish_kuni'] == 'Payshanba') ? 'selected' : '' ?>>Payshanba</option>
                <option value="Juma" <?= ($yetkazib['kelish_kuni'] == 'Juma') ? 'selected' : '' ?>>Juma</option>
                <option value="Shanba" <?= ($yetkazib['kelish_kuni'] == 'Shanba') ? 'selected' : '' ?>>Shanba</option>
                <option value="Yakshanba" <?= ($yetkazib['kelish_kuni'] == 'Yakshanba') ? 'selected' : '' ?>>Yakshanba</option>
                <option value="Har kuni" <?= ($yetkazib['kelish_kuni'] == 'Har kuni') ? 'selected' : '' ?>>Har kuni</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Izoh</label>
            <textarea name="izoh" class="form-control" rows="3"><?= htmlspecialchars($yetkazib['izoh'] ?? '') ?></textarea>
        </div>
        
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="faol" id="faol" value="1" <?= $yetkazib['faol'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="faol">Faol</label>
        </div>
        
        <hr>
        
        <div class="d-flex justify-content-end gap-2">
            <a href="/new-pos/yetkazib" class="btn btn-cancel">Bekor qilish</a>
            <button type="submit" class="btn btn-save">Yangilash</button>
        </div>
    </form>
</div>