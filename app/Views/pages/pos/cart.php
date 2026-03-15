<!-- Page Title -->
<?php $title = 'Savat'; ?>

<div class="page-header">
    <h1>Savat</h1>
</div>

<?php if (empty($cart)): ?>
    <div class="alert alert-info">
        <h4 class="alert-heading">Savat bo'sh</h4>
        <p>Savatda hech narsa yo'q. Mahsulot qo'shish uchun POS sahifasiga qayting.</p>
        <a href="/<?= BASE_PATH ?>/pos" class="btn btn-primary">POS ga qaytish</a>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Savatdagi mahsulotlar</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mahsulot</th>
                                    <th>Narx</th>
                                    <th>Miqdor</th>
                                    <th>Jami</th>
                                    <th>Amal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($item['nomi']) ?></strong><br>
                                            <small class="text-muted">Kod: <?= htmlspecialchars($item['shtrix_kod']) ?></small>
                                        </td>
                                        <td><?= number_format($item['sotish_narxi'], 2, ',', ' ') ?> so'm</td>
                                        <td>
                                            <div class="input-group input-group-sm" style="width: 120px;">
                                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity(<?= $item['id'] ?>, <?= $item['miqdor'] - 1 ?>)">-</button>
                                                <input type="number" class="form-control text-center" value="<?= $item['miqdor'] ?>" min="0.001" step="0.001" onchange="updateQuantity(<?= $item['id'] ?>, this.value)">
                                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity(<?= $item['id'] ?>, <?= $item['miqdor'] + 1 ?>)">+</button>
                                            </div>
                                        </td>
                                        <td><strong><?= number_format($item['total'], 2, ',', ' ') ?> so'm</strong></td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" onclick="removeFromCart(<?= $item['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Chek</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Jami summa:</strong>
                        <h3 class="text-primary mb-0" id="total-amount">
                            <?= number_format($total, 2, ',', ' ') ?> so'm
                        </h3>
                    </div>

                    <form action="/<?= BASE_PATH ?>/pos/checkout" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>">

                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Mijoz (ixtiyoriy)</label>
                            <select name="mijoz_id" id="customer_id" class="form-select">
                                <option value="">Tanlanmagan</option>
                                <!-- Mijozlar AJAX orqali yuklanadi -->
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="discount_amount" class="form-label">Chegirma (so'm)</label>
                            <input type="number" name="chegirma_summa" id="discount_amount" class="form-control" value="0" min="0" step="0.01">
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">To'lov usuli</label>
                            <select name="tolov_usuli" id="payment_method" class="form-select" required>
                                <option value="NAQD">Naqd</option>
                                <option value="KARTA">Karta</option>
                                <option value="NASIYA">Nasiya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="paid_amount" class="form-label">To'langan summa</label>
                            <input type="number" name="tolangan_summa" id="paid_amount" class="form-control" value="<?= $total ?>" min="0" step="0.01" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check"></i> Savdoni yakunlash
                            </button>
                            <a href="/<?= BASE_PATH ?>/pos" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Ortga
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
// Savatni yangilash
function updateQuantity(productId, quantity) {
    if (quantity <= 0) {
        if (confirm('Mahsulotni savatdan olib tashlashni xohlaysizmi?')) {
            removeFromCart(productId);
        }
        return;
    }

    fetch('/<?= BASE_PATH ?>/pos/update-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'csrf_token=<?= generate_csrf() ?>&product_id=' + productId + '&quantity=' + quantity
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Xatolik: ' + data.message);
        }
    });
}

// Mahsulotni olib tashlash
function removeFromCart(productId) {
    fetch('/<?= BASE_PATH ?>/pos/remove-from-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'csrf_token=<?= generate_csrf() ?>&product_id=' + productId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Xatolik: ' + data.message);
        }
    });
}

// Chegirma o'zgarishida jami summani yangilash
document.getElementById('discount_amount').addEventListener('input', function() {
    const discount = parseFloat(this.value) || 0;
    const originalTotal = <?= $total ?>;
    const newTotal = Math.max(0, originalTotal - discount);
    document.getElementById('total-amount').textContent = newTotal.toLocaleString('uz-UZ') + ' so\'m';
    document.getElementById('paid_amount').value = newTotal;
});
</script>