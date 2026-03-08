<!-- Page Title -->
<?php $title = 'POS - Savdo'; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .pos-container {
        display: grid;
        grid-template-columns: 1fr 450px;
        gap: 20px;
    }

    /* Mahsulotlar bo‘limi – avvalgidek */
    .products-section {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .search-box {
        margin-bottom: 20px;
    }
    .category-tabs {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .category-tab {
        padding: 8px 16px;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 20px;
        color: #666;
        white-space: nowrap;
        cursor: pointer;
    }
    .category-tab.active, .category-tab:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
        max-height: 600px;
        overflow-y: auto;
    }
    .product-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        cursor: pointer;
        transition: 0.3s;
        border: 2px solid transparent;
    }
    .product-card:hover {
        transform: translateY(-5px);
        border-color: #667eea;
    }
    .product-card.out-of-stock {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .product-card .barcode {
        font-size: 10px;
        color: #999;
    }
    .product-card .name {
        font-weight: 600;
        margin: 5px 0;
    }
    .product-card .price {
        font-size: 18px;
        font-weight: 700;
        color: #667eea;
    }

    /* Savat bo‘limi – yangi dizayn */
    .cart-section {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        height: fit-content;
        max-height: calc(100vh - 150px);
        overflow-y: auto;
    }
    .cart-header {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 15px;
    }
    .cart-header i {
        color: #667eea;
        margin-right: 8px;
    }
    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }
    .cart-table th {
        text-align: left;
        padding: 8px 5px;
        color: #666;
        font-weight: 600;
        font-size: 13px;
        border-bottom: 1px solid #e0e0e0;
    }
    .cart-table td {
        padding: 10px 5px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }
    .cart-product-name {
        font-weight: 600;
        color: #333;
    }
    .cart-product-sku {
        font-size: 11px;
        color: #999;
        display: block;
    }
    .cart-qty-input {
        width: 60px;
        padding: 5px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        text-align: center;
    }
    .cart-remove {
        color: #dc3545;
        cursor: pointer;
        margin-left: 5px;
    }
    .cart-price {
        font-weight: 600;
        color: #667eea;
    }
    .cart-message {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 12px;
        margin: 15px 0;
        font-size: 14px;
        color: #333;
        border-radius: 4px;
    }
    .cart-message i {
        color: #667eea;
        margin-right: 8px;
    }
    .function-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 15px 0;
        padding: 10px 0;
        border-top: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
    }
    .func-btn {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 20px;
        padding: 6px 15px;
        font-size: 13px;
        font-weight: 500;
        color: #555;
        cursor: pointer;
        transition: 0.2s;
    }
    .func-btn:hover {
        background: #e9ecef;
        border-color: #adb5bd;
    }
    .func-btn i {
        margin-right: 5px;
        color: #667eea;
    }
    .totals {
        margin: 15px 0;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        font-size: 15px;
    }
    .total-row.final {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        border-top: 2px solid #e0e0e0;
        padding-top: 12px;
        margin-top: 8px;
    }
    .confirm-btn {
        width: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 30px;
        padding: 15px;
        color: white;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }
    .confirm-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
    .confirm-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Shift info */
    .shift-info {
        background: #e7f5ff;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-left: 4px solid #667eea;
    }
    .shift-info i {
        color: #667eea;
        font-size: 20px;
    }
    .shift-text {
        flex: 1;
        font-size: 14px;
    }
    .shift-actions {
        display: flex;
        gap: 10px;
    }

    /* Modal stillari avvalgidek */
    .modal-content { border-radius: 12px; }
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0;
    }
    .modal-header .btn-close { filter: brightness(0) invert(1); }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Page Content -->
<?php if (!$smena): ?>
    <!-- Smena ochilmagan bo‘lsa -->
    <div class="shift-info">
        <i class="fas fa-info-circle"></i>
        <div class="shift-text">
            <strong>Smena ochilmagan!</strong> Savdo qilish uchun smena ochishingiz kerak.
        </div>
        <div class="shift-actions">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#openShiftModal">
                <i class="fas fa-play"></i> Smena ochish
            </button>
        </div>
    </div>
    <style>.pos-container { display: none; }</style>
<?php else: ?>
    <!-- Smena ochiq -->
    <div class="shift-info">
        <i class="fas fa-check-circle text-success"></i>
        <div class="shift-text">
            <strong>Smena ochiq</strong> | Ochilgan: <?= date('d.m.Y H:i', strtotime($smena['ochilgan_vaqt'])) ?> | 
            Boshlang'ich naqd: <?= number_format($smena['ochilish_naqd'], 0, ',', ' ') ?> so'm
        </div>
        <div class="shift-actions">
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#closeShiftModal">
                <i class="fas fa-stop"></i> Smena yopish
            </button>
        </div>
    </div>

    <!-- POS Container -->
    <div class="pos-container">
        <!-- Mahsulotlar bo‘limi (chap) -->
        <div class="products-section">
            <div class="search-box">
                <div class="input-group">
                    <input type="text" id="searchProduct" class="form-control" placeholder="Mahsulot qidirish (nomi yoki shtrix kod)" autofocus>
                    <button class="btn btn-primary" type="button" id="searchBtn"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="category-tabs" id="categoryTabs">
                <div class="category-tab active" data-category="all">Barchasi</div>
                <?php foreach ($categories as $category): ?>
                <div class="category-tab" data-category="<?= $category['id'] ?>"><?= htmlspecialchars($category['nomi']) ?></div>
                <?php endforeach; ?>
            </div>
            <div class="products-grid" id="productsGrid">
                <?php foreach ($products as $product): ?>
                <?php if ($product['faol']): ?>
                <div class="product-card <?= $product['miqdor'] <= 0 ? 'out-of-stock' : '' ?>"
                     data-id="<?= $product['id'] ?>"
                     data-name="<?= htmlspecialchars($product['nomi']) ?>"
                     data-price="<?= $product['sotish_narxi'] ?>"
                     data-stock="<?= $product['miqdor'] ?>"
                     data-category="<?= $product['kategoriya_id'] ?>"
                     onclick="addToCart(<?= $product['id'] ?>)">
                    <div class="barcode"><?= $product['shtrix_kod'] ?></div>
                    <div class="name"><?= htmlspecialchars($product['nomi']) ?></div>
                    <div class="price"><?= number_format($product['sotish_narxi'], 0, ',', ' ') ?> so'm</div>
                    <div class="stock <?= $product['miqdor'] <= $product['minimal_miqdor'] ? 'low' : '' ?>">
                        <i class="fas fa-box"></i> <?= $product['miqdor'] ?> <?= $product['birlik'] ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Savat bo‘limi (o‘ng) – yangi dizayn -->
        <div class="cart-section" id="cartSection">
            <div class="cart-header">
                <i class="fas fa-shopping-bag"></i> SAVAT
            </div>

            <!-- Savat jadvali -->
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>MAHSULOT</th>
                        <th>MIQDOR</th>
                        <th>NARXI</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="cartItems">
                    <?php if (empty($cart)): ?>
                    <tr><td colspan="4" class="text-center py-4">Savat bo‘sh</td></tr>
                    <?php else: ?>
                        <?php 
                        $subtotal = 0;
                        foreach ($cart as $item): 
                            $subtotal += $item['total'];
                        ?>
                        <tr data-id="<?= $item['id'] ?>">
                            <td>
                                <span class="cart-product-name"><?= htmlspecialchars($item['name']) ?></span>
                                <span class="cart-product-sku"><?= $item['barcode'] ?></span>
                            </td>
                            <td>
                                <input type="number" class="cart-qty-input" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" onchange="updateCartItem(<?= $item['id'] ?>, this.value)">
                                <i class="fas fa-trash cart-remove" onclick="removeFromCart(<?= $item['id'] ?>)"></i>
                            </td>
                            <td class="cart-price"><?= number_format($item['price'], 0, ',', ' ') ?> so'm</td>
                            <td class="cart-price"><?= number_format($item['total'], 0, ',', ' ') ?> so'm</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Mijoz balansi haqida xabar (agar mijoz tanlangan bo‘lsa) -->
            <?php if (!empty($cart) && isset($customerId)): ?>
            <div class="cart-message">
                <i class="fas fa-info-circle"></i>
                Balans: <strong><?= number_format($subtotal, 0, ',', ' ') ?> so'm</strong>
                <?php if ($debt > 0): ?> (shundan qarz: <?= number_format($debt, 0, ',', ' ') ?> so'm)<?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Funksiya tugmalari -->
            <div class="function-buttons">
                <span class="func-btn"><i class="fas fa-tag"></i> SELL ITEM</span>
                <span class="func-btn"><i class="fas fa-percent"></i> DISCOUNTS</span>
                <span class="func-btn"><i class="fas fa-undo-alt"></i> RETURNS</span>
                <span class="func-btn"><i class="fas fa-credit-card"></i> VOUCHERS</span>
                <span class="func-btn"><i class="fas fa-user"></i> USER FUNCTIONS</span>
                <span class="func-btn"><i class="fas fa-exchange-alt"></i> TRANSACTION FUNC</span>
                <span class="func-btn"><i class="fas fa-cog"></i> OTHER FUNC</span>
            </div>

            <!-- Chegirma va summalar -->
            <div class="discount-section mb-3">
                <label class="form-label fw-bold">Chegirma</label>
                <div class="row g-2">
                    <div class="col-4">
                        <select id="discountType" class="form-select" onchange="toggleDiscountType()">
                            <option value="fixed">So'm</option>
                            <option value="percent">Foiz (%)</option>
                        </select>
                    </div>
                    <div class="col-5">
                        <input type="text" id="discountValue" class="form-control" placeholder="0" value="0"
                               onkeyup="this.value = this.value.replace(/[^0-9]/g, ''); calculateDiscount()">
                    </div>
                    <div class="col-3">
                        <button class="btn btn-outline-danger w-100" onclick="clearDiscount()"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div id="discountInfo" class="mt-2 text-success" style="display:none;">
                    Chegirma: <span id="discountAmount">0</span> so'm
                </div>
            </div>

            <div class="totals">
                <div class="total-row">
                    <span>Sub Total</span>
                    <span id="subtotal"><?= number_format($subtotal ?? 0, 0, ',', ' ') ?> so'm</span>
                </div>
                <div class="total-row" id="discountRow" style="display: none;">
                    <span>Chegirma</span>
                    <span class="text-danger" id="discountDisplay">-0 so'm</span>
                </div>
                <div class="total-row final">
                    <span>JAMI</span>
                    <span id="cartTotal"><?= number_format($subtotal ?? 0, 0, ',', ' ') ?> so'm</span>
                </div>
            </div>

            <!-- Mijoz tanlash va to‘lov -->
            <div class="customer-select mb-3">
                <select id="customerId" class="form-select">
                    <option value="">Mijoz tanlash (ixtiyoriy)</option>
                    <?php foreach ($customers as $customer): ?>
                    <option value="<?= $customer['id'] ?>"><?= htmlspecialchars($customer['fio']) ?> - <?= $customer['telefon'] ?? 'Tel yo‘q' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="payment-methods mb-3">
                <span class="func-btn payment-method <?= (!isset($selectedMethod) || $selectedMethod=='NAQD') ? 'active' : '' ?>" data-method="NAQD" onclick="selectPaymentMethod('NAQD')"><i class="fas fa-money-bill-wave"></i> Naqd</span>
                <span class="func-btn payment-method" data-method="KARTA" onclick="selectPaymentMethod('KARTA')"><i class="fas fa-credit-card"></i> Karta</span>
                <span class="func-btn payment-method" data-method="ARALASH" onclick="selectPaymentMethod('ARALASH')"><i class="fas fa-combine"></i> Aralash</span>
            </div>

            <div class="paid-amount mb-3">
                <input type="text" id="paidAmount" class="form-control" placeholder="To‘langan summa" value="<?= $subtotal ?? 0 ?>" onkeyup="this.value = this.value.replace(/[^0-9]/g, ''); calculateChange()">
            </div>

            <div class="change-amount mb-3" id="changeAmount" style="display:none;">
                Qaytim: <span id="changeValue">0</span> so'm
            </div>

            <div class="mb-3">
                <textarea id="note" class="form-control" rows="2" placeholder="Izoh (ixtiyoriy)"></textarea>
            </div>

            <!-- CONFIRM tugmasi -->
            <button class="confirm-btn" id="checkoutBtn" onclick="checkout()" <?= empty($cart) ? 'disabled' : '' ?>>
                <i class="fas fa-check-circle"></i> CONFIRM
            </button>
        </div>
    </div>
<?php endif; ?>

<!-- Smena ochish modal -->
<div class="modal fade" id="openShiftModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Smena ochish</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/new-pos/pos/open-shift">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Boshlang'ich naqd pul</label>
                        <input type="text" name="opening_cash" class="form-control" value="0" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="submit" class="btn btn-primary">Smena ochish</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Smena yopish modal -->
<div class="modal fade" id="closeShiftModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Smena yopish</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/new-pos/pos/close-shift">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Yakuniy naqd pul</label>
                        <input type="text" name="closing_cash" class="form-control" value="0" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                    <button type="submit" class="btn btn-warning">Smena yopish</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
let selectedPaymentMethod = 'NAQD';
let subtotal = <?= $subtotal ?? 0 ?>;

// ================== SAVAT FUNKSIYALARI ==================
function addToCart(productId) {
    const product = document.querySelector(`.product-card[data-id="${productId}"]`);
    if (product.classList.contains('out-of-stock')) {
        alert('Mahsulot omborda mavjud emas!');
        return;
    }
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/new-pos/pos/add-to-cart';
    form.innerHTML = `
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="product_id" value="${productId}">
        <input type="hidden" name="quantity" value="1">
    `;
    document.body.appendChild(form);
    form.submit();
}

function updateCartItem(productId, quantity) {
    fetch('/new-pos/pos/update-cart', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: quantity, csrf_token: '<?= csrf_token() ?>' })
    })
    .then(response => response.json())
    .then(data => data.success ? location.reload() : alert(data.error));
}

function removeFromCart(productId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/new-pos/pos/remove-from-cart';
    form.innerHTML = `
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="product_id" value="${productId}">
    `;
    document.body.appendChild(form);
    form.submit();
}

function clearCart() {
    if (confirm('Savatni tozalashni xohlaysizmi?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/new-pos/pos/clear-cart';
        form.innerHTML = `<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">`;
        document.body.appendChild(form);
        form.submit();
    }
}

// ================== CHEGIRMA ==================
function calculateDiscount() {
    const discountType = document.getElementById('discountType').value;
    const discountValue = parseFloat(document.getElementById('discountValue').value.replace(/[^0-9]/g, '')) || 0;
    let discountAmount = 0;
    if (discountType === 'percent' && discountValue > 0) {
        discountAmount = (subtotal * discountValue) / 100;
    } else if (discountType === 'fixed' && discountValue > 0) {
        discountAmount = discountValue;
    }
    if (discountAmount > subtotal) discountAmount = subtotal;
    const total = subtotal - discountAmount;

    // Display
    if (discountAmount > 0) {
        document.getElementById('discountRow').style.display = 'flex';
        document.getElementById('discountDisplay').textContent = '-' + discountAmount.toLocaleString() + ' so‘m';
        document.getElementById('discountInfo').style.display = 'block';
        document.getElementById('discountAmount').textContent = discountAmount.toLocaleString();
    } else {
        document.getElementById('discountRow').style.display = 'none';
        document.getElementById('discountInfo').style.display = 'none';
    }
    document.getElementById('cartTotal').textContent = total.toLocaleString() + ' so‘m';
    document.getElementById('paidAmount').value = total;
    calculateChange();
}

function toggleDiscountType() {
    document.getElementById('discountValue').value = '0';
    calculateDiscount();
}

function clearDiscount() {
    document.getElementById('discountType').value = 'fixed';
    document.getElementById('discountValue').value = '0';
    calculateDiscount();
}

// ================== TO‘LOV ==================
function selectPaymentMethod(method) {
    selectedPaymentMethod = method;
    document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('active'));
    document.querySelector(`.payment-method[data-method="${method}"]`).classList.add('active');
}

function calculateChange() {
    const total = parseFloat(document.getElementById('cartTotal').textContent.replace(/[^0-9]/g, '')) || 0;
    const paid = parseFloat(document.getElementById('paidAmount').value.replace(/[^0-9]/g, '')) || 0;
    if (paid >= total) {
        const change = paid - total;
        document.getElementById('changeValue').textContent = change.toLocaleString() + ' so‘m';
        document.getElementById('changeAmount').style.display = 'block';
    } else {
        document.getElementById('changeAmount').style.display = 'none';
    }
}

function checkout() {
    const items = document.querySelectorAll('#cartItems tr');
    if (items.length === 0 || (items.length === 1 && items[0].cells.length === 1)) {
        alert('Savat bo‘sh');
        return;
    }
    const total = parseFloat(document.getElementById('cartTotal').textContent.replace(/[^0-9]/g, '')) || 0;
    const paid = parseFloat(document.getElementById('paidAmount').value.replace(/[^0-9]/g, '')) || 0;
    const customerId = document.getElementById('customerId').value;
    const note = document.getElementById('note').value;
    const discountType = document.getElementById('discountType').value;
    const discountValue = document.getElementById('discountValue').value;

    if (paid <= 0 && customerId) {
        if (!confirm('To‘langan summa 0. Nasiya qilishni xohlaysizmi?')) return;
    }
    if (paid < total && !customerId) {
        alert('To‘lov yetarli emas. Nasiya uchun mijoz tanlang.');
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/new-pos/pos/checkout';
    form.innerHTML = `
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="payment_method" value="${selectedPaymentMethod}">
        <input type="hidden" name="paid_amount" value="${paid}">
        <input type="hidden" name="customer_id" value="${customerId}">
        <input type="hidden" name="note" value="${note}">
        <input type="hidden" name="discount_type" value="${discountType}">
        <input type="hidden" name="discount_value" value="${discountValue}">
    `;
    document.body.appendChild(form);
    form.submit();
}

// ================== QIDIRUV VA FILTER ==================
function searchProducts() {
    const term = document.getElementById('searchProduct').value.toLowerCase();
    const products = document.querySelectorAll('.product-card');
    let visible = 0;
    products.forEach(p => {
        const name = p.querySelector('.name').textContent.toLowerCase();
        const barcode = p.querySelector('.barcode').textContent.toLowerCase();
        const match = name.includes(term) || barcode.includes(term);
        p.style.display = match ? 'block' : 'none';
        if (match) visible++;
    });
    const emptyMsg = document.getElementById('emptySearchMessage');
    if (visible === 0) {
        if (!emptyMsg) {
            const msg = document.createElement('div');
            msg.id = 'emptySearchMessage';
            msg.className = 'empty-products';
            msg.innerHTML = '<i class="fas fa-search"></i><p>Hech qanday mahsulot topilmadi</p>';
            document.getElementById('productsGrid').appendChild(msg);
        }
    } else if (emptyMsg) emptyMsg.remove();
}

document.getElementById('searchBtn').addEventListener('click', searchProducts);
document.getElementById('searchProduct').addEventListener('keyup', e => { if (e.key === 'Enter') searchProducts(); });

document.querySelectorAll('.category-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        const catId = this.dataset.category;
        document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        document.querySelectorAll('.product-card').forEach(p => {
            if (catId === 'all' || p.dataset.category === catId) {
                p.style.display = 'block';
            } else {
                p.style.display = 'none';
            }
        });
    });
});

// ================== KLAVIATURA QISQARTMALARI ==================
document.addEventListener('keydown', e => {
    if (e.key === 'F2') { e.preventDefault(); document.getElementById('searchProduct').focus(); }
    if (e.key === 'F3') { e.preventDefault(); clearCart(); }
    if (e.key === 'F4') { e.preventDefault(); checkout(); }
    if (e.key === '1') selectPaymentMethod('NAQD');
    if (e.key === '2') selectPaymentMethod('KARTA');
    if (e.key === '3') selectPaymentMethod('ARALASH');
});

// Barcode scanner
let barcodeBuffer = '';
let barcodeTimeout;
document.addEventListener('keypress', e => {
    if (e.key.length === 1) {
        barcodeBuffer += e.key;
        clearTimeout(barcodeTimeout);
        barcodeTimeout = setTimeout(() => {
            if (barcodeBuffer.length > 3) {
                const products = document.querySelectorAll('.product-card');
                let found = false;
                products.forEach(p => {
                    if (p.querySelector('.barcode').textContent === barcodeBuffer) {
                        found = true;
                        if (!p.classList.contains('out-of-stock')) addToCart(p.dataset.id);
                    }
                });
                if (!found) alert('Mahsulot topilmadi: ' + barcodeBuffer);
            }
            barcodeBuffer = '';
        }, 100);
    }
});

// Initial
document.addEventListener('DOMContentLoaded', () => {
    calculateChange();
    document.getElementById('searchProduct').focus();
    const totalEl = document.getElementById('cartTotal');
    if (totalEl) subtotal = parseFloat(totalEl.textContent.replace(/[^0-9]/g, '')) || 0;
});
</script>
<?php $extraJs = ob_get_clean(); ?>