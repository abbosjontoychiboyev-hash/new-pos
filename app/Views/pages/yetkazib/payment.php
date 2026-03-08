<!-- Page Title -->
<?php $title = 'To‘lov qo‘shish - ' . htmlspecialchars($diller['nomi']); ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .payment-card {
        max-width: 600px;
        margin: 0 auto;
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<div class="payment-card">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-money-bill-wave"></i> To‘lov qo‘shish</h5>
            <a href="/new-pos/yetkazib/view/<?= $diller['id'] ?>" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Orqaga</a>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Diller:</strong> <?= htmlspecialchars($diller['nomi']) ?><br>
                <strong>Joriy qarz:</strong> <?= number_format($diller['qarz'], 0, ',', ' ') ?> so‘m
            </div>

            <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['flash']['error'] ?></div>
                <?php unset($_SESSION['flash']['error']); ?>
            <?php endif; ?>

            <form method="POST" action="/new-pos/yetkazib/store-payment">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="yetkazib_beruvchi_id" value="<?= $diller['id'] ?>">

                <div class="mb-3">
                    <label class="form-label">To‘lov summasi <span class="text-danger">*</span></label>
                    <input type="text" name="summa" class="form-control" placeholder="0" value="<?= $diller['qarz'] ?>" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">To‘lov usuli <span class="text-danger">*</span></label>
                    <select name="usul" class="form-select">
                        <option value="NAQD">Naqd</option>
                        <option value="KARTA">Plastik karta</option>
                        <option value="OTKAZMA">Pul o‘tkazma</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Izoh (ixtiyoriy)</label>
                    <textarea name="izoh" class="form-control" rows="2"></textarea>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Saqlash</button>
                <a href="/new-pos/yetkazib/show/<?= $diller['id'] ?>" class="btn btn-secondary">Bekor qilish</a>
            </form>
        </div>
    </div>
</div>