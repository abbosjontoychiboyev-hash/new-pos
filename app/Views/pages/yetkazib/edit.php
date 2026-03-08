<!-- Page Title -->
<?php $title = 'Diller tahrirlash: ' . htmlspecialchars($diller['nomi']); ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .form-card {
        max-width: 800px;
        margin: 0 auto;
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<div class="form-card">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-edit"></i> Diller tahrirlash</h5>
            <a href="/new-pos/yetkazib" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Orqaga
            </a>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['flash']['error'] ?></div>
                <?php unset($_SESSION['flash']['error']); ?>
            <?php endif; ?>

            <form method="POST" action="/new-pos/yetkazib/update/<?= $diller['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Diller nomi <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nomi" 
                               class="form-control <?= isset($_SESSION['errors']['nomi']) ? 'is-invalid' : '' ?>" 
                               value="<?= isset($_SESSION['old']['nomi']) ? htmlspecialchars($_SESSION['old']['nomi']) : htmlspecialchars($diller['nomi']) ?>" 
                               required>
                        <?php if (isset($_SESSION['errors']['nomi'])): ?>
                            <div class="invalid-feedback"><?= implode(', ', $_SESSION['errors']['nomi']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telefon</label>
                        <input type="text" 
                               name="telefon" 
                               class="form-control" 
                               value="<?= isset($_SESSION['old']['telefon']) ? htmlspecialchars($_SESSION['old']['telefon']) : htmlspecialchars($diller['telefon'] ?? '') ?>">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Manzil</label>
                        <input type="text" 
                               name="manzil" 
                               class="form-control" 
                               value="<?= isset($_SESSION['old']['manzil']) ? htmlspecialchars($_SESSION['old']['manzil']) : htmlspecialchars($diller['manzil'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelish kuni</label>
                        <select name="kelish_kuni" class="form-select">
                            <option value="">Tanlanmagan</option>
                            <?php
                            $kunlar = [
                                'Monday'    => 'Dushanba',
                                'Tuesday'   => 'Seshanba',
                                'Wednesday' => 'Chorshanba',
                                'Thursday'  => 'Payshanba',
                                'Friday'    => 'Juma',
                                'Saturday'  => 'Shanba',
                                'Sunday'    => 'Yakshanba'
                            ];
                            $selected = isset($_SESSION['old']['kelish_kuni']) ? $_SESSION['old']['kelish_kuni'] : ($diller['kelish_kuni'] ?? '');
                            foreach ($kunlar as $en => $uz): ?>
                                <option value="<?= $en ?>" <?= $selected == $en ? 'selected' : '' ?>><?= $uz ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">To‘lash muddati (kunlarda)</label>
                        <input type="number" 
                               name="tolash_muddati" 
                               class="form-control" 
                               value="<?= isset($_SESSION['old']['tolash_muddati']) ? $_SESSION['old']['tolash_muddati'] : ($diller['tolash_muddati'] ?? '') ?>" 
                               min="0">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Qisqa izoh</label>
                        <textarea name="izoh" class="form-control" rows="2"><?= isset($_SESSION['old']['izoh']) ? htmlspecialchars($_SESSION['old']['izoh']) : htmlspecialchars($diller['izoh'] ?? '') ?></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">To‘lov eslatmasi</label>
                        <textarea name="tolash_eslatma" class="form-control" rows="2"><?= isset($_SESSION['old']['tolash_eslatma']) ? htmlspecialchars($_SESSION['old']['tolash_eslatma']) : htmlspecialchars($diller['tolash_eslatma'] ?? '') ?></textarea>
                    </div>
                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="/new-pos/yetkazib" class="btn btn-secondary">Bekor qilish</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Yangilash</button>
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