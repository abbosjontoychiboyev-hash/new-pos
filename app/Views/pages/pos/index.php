<!-- Page Title -->
<?php $title = 'POS - Savdo'; ?>

<!-- Shift Info -->
<?php if (!$smena): ?>
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
<?php else: ?>
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
<?php endif; ?>

<!-- POS Container -->
<div class="pos-container">
    <!-- Products Section -->
    <div class="products-section">
        <div class="search-box">
            <div class="input-group">
                <input type="text" 
                       id="searchProduct" 
                       class="form-control" 
                       placeholder="Mahsulot qidirish (nomi yoki shtrix kod)" 
                       autofocus>
                <button class="btn btn-primary" type="button" id="searchBtn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        
        <!-- Category Tabs -->
        <div class="category-tabs" id="categoryTabs">
            <div class="category-tab active" data-category="all">Barchasi</div>
            <?php foreach ($categories as $category): ?>
            <div class="category-tab" data-category="<?= $category['id'] ?>">
                <?= htmlspecialchars($category['nomi']) ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Products Grid -->
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
                    <div class="unit"><?= $product['birlik'] ?></div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Cart Section -->
    <div class="cart-section">
        <div class="cart-header">
            <div class="cart-title">
                <i class="fas fa-shopping-cart"></i> Savat
            </div>
            <div class="cart-clear" onclick="clearCart()">
                <i class="fas fa-trash"></i> Tozalash
            </div>
        </div>
        
        <!-- Cart Items -->
        <div class="cart-items" id="cartItems">
            <?php if (empty($cart)): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Savat bo'sh</p>
                </div>
            <?php else: ?>
                <?php 
                $total = 0;
                foreach ($cart as $item): 
                    $total += $item['total'];
                ?>
                <div class="cart-item" data-id="<?= $item['id'] ?>">
                    <div class="cart-item-info">
                        <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="cart-item-price"><?= number_format($item['price'], 0, ',', ' ') ?> so'm</div>
                    </div>
                    <div class="cart-item-actions">
                        <input type="number" 
                               class="cart-item-quantity" 
                               value="<?= $item['quantity'] ?>" 
                               min="1" 
                               max="<?= $item['stock'] ?>"
                               onchange="updateCartItem(<?= $item['id'] ?>, this.value)">
                        <i class="fas fa-times cart-item-remove" onclick="removeFromCart(<?= $item['id'] ?>)"></i>
                    </div>
                    <div class="cart-item-total"><?= number_format($item['total'], 0, ',', ' ') ?> so'm</div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Discount Section -->
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
                    <input type="text" 
                           id="discountValue" 
                           class="form-control" 
                           placeholder="Chegirma" 
                           value="0"
                           onkeyup="this.value = this.value.replace(/[^0-9]/g, ''); calculateDiscount()">
                </div>
                <div class="col-3">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="clearDiscount()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="discountInfo" class="mt-2 text-success" style="display: none;">
                Chegirma: <span id="discountAmount">0</span> so'm
            </div>
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <div class="summary-row">
                <span>Jami:</span>
                <span class="amount" id="subtotal"><?= number_format($total ?? 0, 0, ',', ' ') ?> so'm</span>
            </div>
            <div class="summary-row" id="discountRow" style="display: none;">
                <span>Chegirma:</span>
                <span class="amount text-danger" id="discountDisplay">0 so'm</span>
            </div>
            <div class="summary-row total">
                <span>Yakuniy:</span>
                <span class="amount" id="cartTotal"><?= number_format($total ?? 0, 0, ',', ' ') ?> so'm</span>
            </div>
            
            <!-- Customer Select -->
            <div class="customer-select">
                <select id="customerId" class="form-select">
                    <option value="">Mijoz tanlash (ixtiyoriy)</option>
                    <?php foreach ($customers as $customer): ?>
                    <option value="<?= $customer['id'] ?>">
                        <?= htmlspecialchars($customer['fio']) ?> - <?= $customer['telefon'] ?? 'Tel yo\'q' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Payment Methods -->
            <div class="payment-methods">
                <div class="payment-method active" data-method="NAQD" onclick="selectPaymentMethod('NAQD')">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Naqd</span>
                </div>
                <div class="payment-method" data-method="KARTA" onclick="selectPaymentMethod('KARTA')">
                    <i class="fas fa-credit-card"></i>
                    <span>Karta</span>
                </div>
                <div class="payment-method" data-method="ARALASH" onclick="selectPaymentMethod('ARALASH')">
                    <i class="fas fa-combine"></i>
                    <span>Aralash</span>
                </div>
            </div>
            
            <!-- Paid Amount -->
            <div class="paid-amount">
                <label>To'langan summa</label>
                <input type="text" 
                       id="paidAmount" 
                       class="form-control" 
                       placeholder="0" 
                       value="<?= $total ?? 0 ?>"
                       onkeyup="this.value = this.value.replace(/[^0-9]/g, ''); calculateChange()">
            </div>
            
            <!-- Change Amount -->
            <div class="change-amount" id="changeAmount">
                Qaytim: <span id="changeValue">0</span> so'm
            </div>
            
            <!-- Note -->
            <div class="mb-3">
                <textarea id="note" class="form-control" rows="2" placeholder="Izoh (ixtiyoriy)"></textarea>
            </div>
            
            <!-- Checkout Button -->
            <button class="checkout-btn" id="checkoutBtn" onclick="checkout()" <?= empty($cart) ? 'disabled' : '' ?>>
                <i class="fas fa-check-circle"></i> Savdoni yakunlash
            </button>
        </div>
    </div>
</div>

<!-- Open Shift Modal -->
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
                        <input type="text" 
                               name="opening_cash" 
                               class="form-control" 
                               value="0"
                               onkeyup="this.value = this.value.replace(/[^0-9]/g, '')"
                               required>
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

<!-- Close Shift Modal -->
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
                        <input type="text" 
                               name="closing_cash" 
                               class="form-control" 
                               value="0"
                               onkeyup="this.value = this.value.replace(/[^0-9]/g, '')"
                               required>
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

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .pos-container {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 20px;
    }
    
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
        scrollbar-width: thin;
    }
    .category-tabs::-webkit-scrollbar {
        height: 5px;
    }
    .category-tabs::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 5px;
    }
    
    .category-tab {
        padding: 8px 16px;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 20px;
        color: #666;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.3s;
        cursor: pointer;
    }
    .category-tab:hover, .category-tab.active {
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
        padding-right: 10px;
    }
    .products-grid::-webkit-scrollbar {
        width: 5px;
    }
    .products-grid::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 5px;
    }
    
    .product-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-5px);
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(102,126,234,0.2);
    }
    .product-card.out-of-stock {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .product-card.out-of-stock:hover {
        transform: none;
        border-color: #dc3545;
    }
    .product-card .barcode {
        font-size: 10px;
        color: #999;
        margin-bottom: 5px;
        font-family: monospace;
    }
    .product-card .name {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        font-size: 14px;
        line-height: 1.3;
    }
    .product-card .price {
        font-size: 18px;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 5px;
    }
    .product-card .stock {
        font-size: 11px;
        color: #28a745;
    }
    .product-card .stock.low {
        color: #dc3545;
    }
    .product-card .unit {
        font-size: 11px;
        color: #999;
    }
    
    .cart-section {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        height: fit-content;
        position: sticky;
        top: 20px;
    }
    
    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    .cart-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    .cart-title i {
        color: #667eea;
        margin-right: 8px;
    }
    .cart-clear {
        color: #dc3545;
        cursor: pointer;
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 5px;
        transition: all 0.3s;
    }
    .cart-clear:hover {
        background: #fee;
    }
    
    .cart-items {
        max-height: 400px;
        overflow-y: auto;
        margin-bottom: 20px;
        padding-right: 5px;
    }
    .cart-items::-webkit-scrollbar {
        width: 5px;
    }
    .cart-items::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 5px;
    }
    
    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 10px;
        border-left: 3px solid #667eea;
    }
    .cart-item-info {
        flex: 1;
    }
    .cart-item-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        font-size: 14px;
    }
    .cart-item-price {
        font-size: 13px;
        color: #667eea;
    }
    .cart-item-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 10px;
    }
    .cart-item-quantity {
        width: 50px;
        text-align: center;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        padding: 4px;
        font-size: 13px;
    }
    .cart-item-quantity:focus {
        outline: none;
        border-color: #667eea;
    }
    .cart-item-remove {
        color: #dc3545;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: all 0.3s;
    }
    .cart-item-remove:hover {
        background: #fee;
    }
    .cart-item-total {
        font-weight: 700;
        color: #333;
        min-width: 80px;
        text-align: right;
        font-size: 14px;
    }
    
    .cart-summary {
        border-top: 2px solid #f0f0f0;
        padding-top: 20px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
        color: #666;
    }
    .summary-row.total {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        border-top: 1px solid #e0e0e0;
        padding-top: 10px;
        margin-top: 10px;
    }
    .summary-row.total .amount {
        color: #667eea;
    }
    
    .customer-select select {
        width: 100%;
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
    }
    .customer-select select:focus {
        outline: none;
        border-color: #667eea;
    }
    
    .payment-methods {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }
    .payment-method {
        flex: 1;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: white;
    }
    .payment-method:hover {
        border-color: #667eea;
        background: #f0f3ff;
    }
    .payment-method.active {
        border-color: #667eea;
        background: #f0f3ff;
    }
    .payment-method i {
        display: block;
        font-size: 20px;
        margin-bottom: 5px;
        color: #667eea;
    }
    .payment-method span {
        font-size: 13px;
        font-weight: 500;
    }
    
    .paid-amount input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
    }
    .paid-amount input:focus {
        outline: none;
        border-color: #667eea;
    }
    
    .change-amount {
        margin-bottom: 15px;
        padding: 10px;
        background: #e7f5ff;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        color: #0c5460;
        display: none;
    }
    .change-amount.show {
        display: block;
    }
    
    .checkout-btn {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        color: white;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    .checkout-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
    .checkout-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
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
    .shift-info .shift-text {
        flex: 1;
        font-size: 14px;
    }
    .shift-info .shift-text strong {
        color: #333;
    }
    .shift-info .shift-actions {
        display: flex;
        gap: 10px;
    }
    
    .empty-cart {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }
    .empty-cart i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #ddd;
    }
    
    .modal-content {
        border-radius: 12px;
        border: none;
    }
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0;
    }
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    let selectedPaymentMethod = 'NAQD';
    let subtotal = <?= $total ?? 0 ?>;
    
    // Select payment method
    function selectPaymentMethod(method) {
        selectedPaymentMethod = method;
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('active');
        });
        document.querySelector(`.payment-method[data-method="${method}"]`).classList.add('active');
    }
    
    // Calculate change
    function calculateChange() {
        const total = parseFloat(document.getElementById('cartTotal').textContent.replace(/[^0-9]/g, '')) || 0;
        const paid = parseFloat(document.getElementById('paidAmount').value.replace(/[^0-9]/g, '')) || 0;
        
        if (paid >= total) {
            const change = paid - total;
            document.getElementById('changeValue').textContent = change.toLocaleString();
            document.getElementById('changeAmount').classList.add('show');
        } else {
            document.getElementById('changeAmount').classList.remove('show');
        }
    }
    
    // Calculate discount
    function calculateDiscount() {
        const discountType = document.getElementById('discountType').value;
        const discountValue = parseFloat(document.getElementById('discountValue').value.replace(/[^0-9]/g, '')) || 0;
        
        let discountAmount = 0;
        
        if (discountType === 'percent' && discountValue > 0) {
            discountAmount = (subtotal * discountValue) / 100;
        } else if (discountType === 'fixed' && discountValue > 0) {
            discountAmount = discountValue;
        }
        
        if (discountAmount > subtotal) {
            discountAmount = subtotal;
            document.getElementById('discountValue').value = discountType === 'percent' 
                ? Math.round((discountAmount / subtotal) * 100) 
                : discountAmount;
        }
        
        const total = subtotal - discountAmount;
        
        if (discountAmount > 0) {
            document.getElementById('discountRow').style.display = 'flex';
            document.getElementById('discountDisplay').textContent = '-' + discountAmount.toLocaleString() + ' so\'m';
            document.getElementById('discountInfo').style.display = 'block';
            document.getElementById('discountAmount').textContent = discountAmount.toLocaleString();
        } else {
            document.getElementById('discountRow').style.display = 'none';
            document.getElementById('discountInfo').style.display = 'none';
        }
        
        document.getElementById('cartTotal').textContent = total.toLocaleString() + ' so\'m';
        
        const paidInput = document.getElementById('paidAmount');
        paidInput.value = total;
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
    
    // Add to cart
    function addToCart(productId) {
        const product = document.querySelector(`.product-card[data-id="${productId}"]`);
        
        if (product.classList.contains('out-of-stock')) {
            alert('Mahsulot omborda mavjud emas!');
            return;
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/new-pos/pos/add-to-cart';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_token';
        csrfInput.value = '<?= csrf_token() ?>';
        
        const productInput = document.createElement('input');
        productInput.type = 'hidden';
        productInput.name = 'product_id';
        productInput.value = productId;
        
        const quantityInput = document.createElement('input');
        quantityInput.type = 'hidden';
        quantityInput.name = 'quantity';
        quantityInput.value = 1;
        
        form.appendChild(csrfInput);
        form.appendChild(productInput);
        form.appendChild(quantityInput);
        
        document.body.appendChild(form);
        form.submit();
    }
    
    // Update cart item quantity
    function updateCartItem(productId, quantity) {
        fetch('/new-pos/pos/update-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity,
                csrf_token: '<?= csrf_token() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Xatolik yuz berdi');
            }
        });
    }
    
    // Remove from cart
    function removeFromCart(productId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/new-pos/pos/remove-from-cart';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_token';
        csrfInput.value = '<?= csrf_token() ?>';
        
        const productInput = document.createElement('input');
        productInput.type = 'hidden';
        productInput.name = 'product_id';
        productInput.value = productId;
        
        form.appendChild(csrfInput);
        form.appendChild(productInput);
        
        document.body.appendChild(form);
        form.submit();
    }
    
    // Clear cart
    function clearCart() {
        if (confirm('Savatni tozalashni xohlaysizmi?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/new-pos/pos/clear-cart';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = '<?= csrf_token() ?>';
            
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Checkout
    function checkout() {
        const cartItems = document.querySelectorAll('.cart-item');
        if (cartItems.length === 0) {
            alert('Savat bo\'sh');
            return;
        }
        
        const total = parseFloat(document.getElementById('cartTotal').textContent.replace(/[^0-9]/g, '')) || 0;
        const paid = parseFloat(document.getElementById('paidAmount').value.replace(/[^0-9]/g, '')) || 0;
        const customerId = document.getElementById('customerId').value;
        const note = document.getElementById('note').value;
        
        const discountType = document.getElementById('discountType').value;
        const discountValue = document.getElementById('discountValue').value;
        
        if (paid <= 0 && customerId) {
            if (!confirm('To\'langan summa 0. Nasiya qilishni xohlaysizmi?')) {
                return;
            }
        }
        
        if (paid < total && !customerId) {
            alert('To\'lov yetarli emas. Nasiya qilish uchun mijoz tanlashingiz kerak.');
            return;
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/new-pos/pos/checkout';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_token';
        csrfInput.value = '<?= csrf_token() ?>';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = 'payment_method';
        methodInput.value = selectedPaymentMethod;
        
        const paidInput = document.createElement('input');
        paidInput.type = 'hidden';
        paidInput.name = 'paid_amount';
        paidInput.value = paid;
        
        const customerInput = document.createElement('input');
        customerInput.type = 'hidden';
        customerInput.name = 'customer_id';
        customerInput.value = customerId;
        
        const noteInput = document.createElement('input');
        noteInput.type = 'hidden';
        noteInput.name = 'note';
        noteInput.value = note;
        
        const discountTypeInput = document.createElement('input');
        discountTypeInput.type = 'hidden';
        discountTypeInput.name = 'discount_type';
        discountTypeInput.value = discountType;
        
        const discountValueInput = document.createElement('input');
        discountValueInput.type = 'hidden';
        discountValueInput.name = 'discount_value';
        discountValueInput.value = discountValue;
        
        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        form.appendChild(paidInput);
        form.appendChild(customerInput);
        form.appendChild(noteInput);
        form.appendChild(discountTypeInput);
        form.appendChild(discountValueInput);
        
        document.body.appendChild(form);
        form.submit();
    }
    
    // Search products
    function searchProducts() {
        const searchTerm = document.getElementById('searchProduct').value.toLowerCase();
        const products = document.querySelectorAll('.product-card');
        let visibleCount = 0;
        
        products.forEach(product => {
            const name = product.querySelector('.name').textContent.toLowerCase();
            const barcode = product.querySelector('.barcode').textContent.toLowerCase();
            
            if (name.includes(searchTerm) || barcode.includes(searchTerm)) {
                product.style.display = 'block';
                visibleCount++;
            } else {
                product.style.display = 'none';
            }
        });
        
        const emptyMessage = document.getElementById('emptySearchMessage');
        if (visibleCount === 0) {
            if (!emptyMessage) {
                const message = document.createElement('div');
                message.id = 'emptySearchMessage';
                message.className = 'empty-products';
                message.innerHTML = '<i class="fas fa-search"></i><p>Hech qanday mahsulot topilmadi</p>';
                document.getElementById('productsGrid').appendChild(message);
            }
        } else {
            if (emptyMessage) {
                emptyMessage.remove();
            }
        }
    }
    
    // Filter by category
    function filterByCategory(categoryId) {
        const products = document.querySelectorAll('.product-card');
        const categoryTabs = document.querySelectorAll('.category-tab');
        
        categoryTabs.forEach(tab => {
            tab.classList.remove('active');
            if (tab.dataset.category == categoryId || (categoryId === 'all' && tab.dataset.category === 'all')) {
                tab.classList.add('active');
            }
        });
        
        products.forEach(product => {
            if (categoryId === 'all') {
                product.style.display = 'block';
            } else {
                const productCategory = product.dataset.category;
                if (productCategory == categoryId) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            }
        });
    }
    
    // Event listeners
    document.getElementById('searchProduct')?.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            searchProducts();
        }
    });
    
    document.getElementById('searchBtn')?.addEventListener('click', searchProducts);
    
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            filterByCategory(this.dataset.category);
        });
    });
    
    document.getElementById('paidAmount')?.addEventListener('keyup', calculateChange);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F2') {
            e.preventDefault();
            document.getElementById('searchProduct')?.focus();
        }
        
        if (e.key === 'F3') {
            e.preventDefault();
            if (confirm('Savatni tozalashni xohlaysizmi?')) {
                clearCart();
            }
        }
        
        if (e.key === 'F4') {
            e.preventDefault();
            checkout();
        }
        
        if (e.key === 'F5') {
            e.preventDefault();
            filterByCategory('all');
            document.getElementById('searchProduct').value = '';
            searchProducts();
        }
        
        if (e.key === '1') selectPaymentMethod('NAQD');
        if (e.key === '2') selectPaymentMethod('KARTA');
        if (e.key === '3') selectPaymentMethod('ARALASH');
    });
    
    // Barcode scanner support
    let barcodeBuffer = '';
    let barcodeTimeout;
    
    document.addEventListener('keypress', function(e) {
        if (e.key.length === 1) {
            barcodeBuffer += e.key;
            
            clearTimeout(barcodeTimeout);
            barcodeTimeout = setTimeout(function() {
                if (barcodeBuffer.length > 3) {
                    const products = document.querySelectorAll('.product-card');
                    let found = false;
                    
                    products.forEach(product => {
                        const barcode = product.querySelector('.barcode').textContent;
                        if (barcode === barcodeBuffer) {
                            found = true;
                            const productId = product.dataset.id;
                            if (!product.classList.contains('out-of-stock')) {
                                addToCart(productId);
                            }
                        }
                    });
                    
                    if (!found) {
                        alert('Mahsulot topilmadi: ' + barcodeBuffer);
                    }
                }
                barcodeBuffer = '';
            }, 100);
        }
    });
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        calculateChange();
        document.getElementById('searchProduct')?.focus();
        
        const totalElement = document.getElementById('cartTotal');
        if (totalElement) {
            subtotal = parseFloat(totalElement.textContent.replace(/[^0-9]/g, '')) || 0;
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<?php 
// Clear old data
unset($_SESSION['old']);
unset($_SESSION['errors']);
?>