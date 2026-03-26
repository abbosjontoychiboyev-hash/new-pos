<!-- Page Title -->
<?php $title = 'POS - Savdo'; ?>

<!-- Extra CSS -->
<?php $extraCss = ''; ?>
<style>
    :root{
        --pos-bg:#667eea;
        --panel:#f6f8ff;
        --panel-2:#ffffff;
        --line:#dbe1ff;
        --primary:#667eea;
        --primary-dark:#5568db;
        --text:#666666;
        --muted:#8d8d8d;
        --danger:#dc3545;
        --success:#4f46e5;
        --shadow:0 12px 28px rgba(102,126,234,.12);
    }

    .pos-page{
        background:linear-gradient(180deg, rgba(102,126,234,.06) 0%, rgba(102,126,234,.02) 100%);
        border-radius:20px;
        padding:10px;
    }

    .pos-container{
        display:grid;
        grid-template-columns:500px 1000px;
        gap:20px;
        align-items:start;
        justify-content:center;
        min-height:calc(100vh - 150px);
        max-width:1520px;
        margin:0 auto;
    }

    .products-section,
    .cart-section{
        background:var(--panel);
        border:1px solid var(--line);
        border-radius:18px;
        box-shadow:var(--shadow);
        min-height:0;
        color:var(--text);
    }

    /* CHAP: MAHSULOTLAR */
    .products-section{
        padding:14px;
        display:flex;
        flex-direction:column;
        height:calc(100vh - 150px);
        overflow:hidden;
    }

    .products-toolbar{
        flex-shrink:0;
        padding-bottom:12px;
        margin-bottom:12px;
        border-bottom:1px solid var(--line);
    }

    .toolbar-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        margin-bottom:10px;
    }

    .toolbar-title{
        font-size:16px;
        font-weight:800;
        letter-spacing:.08em;
        color:var(--text);
    }

    .toolbar-subtitle{
        font-size:12px;
        color:var(--muted);
        margin-top:2px;
    }

    .search-box .input-group{
        border:1px solid var(--line);
        border-radius:12px;
        overflow:hidden;
        background:#fff;
    }

    .search-box .form-control{
        border:none;
        box-shadow:none;
        height:48px;
        font-size:15px;
        color:var(--text);
    }

    .search-box .form-control::placeholder{
        color:#9aa0b5;
    }

    .search-box .btn{
        border:none;
        background:var(--primary);
        color:#fff;
        padding:0 18px;
    }

    .search-box .btn:hover{
        background:var(--primary-dark);
    }

    .products-scroll{
        flex:1;
        min-height:0;
        overflow-y:auto;
        padding-right:4px;
    }

    .products-grid{
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(145px,1fr));
        gap:12px;
        align-content:flex-start;
    }

    .product-card{
        background:#fff;
        border:1px solid var(--line);
        border-radius:14px;
        padding:12px;
        cursor:pointer;
        transition:.18s ease;
        min-height:138px;
        display:flex;
        flex-direction:column;
        justify-content:space-between;
    }

    .product-card:hover{
        transform:translateY(-2px);
        border-color:var(--primary);
        box-shadow:0 12px 24px rgba(102,126,234,.14);
    }

    .product-card.out-of-stock{
        opacity:.5;
        cursor:not-allowed;
    }

    .product-card .barcode{
        font-size:11px;
        color:var(--muted);
        line-height:1.2;
        margin-bottom:7px;
        word-break:break-all;
    }

    .product-card .name{
        font-size:14px;
        font-weight:700;
        color:var(--text);
        line-height:1.35;
        min-height:38px;
        margin-bottom:10px;
    }

    .product-card .price{
        font-size:19px;
        font-weight:800;
        color:var(--primary-dark);
        margin-bottom:6px;
    }

    .product-card .stock{
        font-size:12px;
        color:var(--muted);
    }

    .product-card .stock.low{
        color:var(--danger);
        font-weight:700;
    }

    .empty-products{
        width:100%;
        text-align:center;
        padding:40px 20px;
        color:var(--muted);
    }

    .empty-products i{
        font-size:36px;
        margin-bottom:10px;
        color:#bcc4ef;
    }

    /* O'NG: SAVATCHA */
    .cart-section{
        padding:12px;
        display:flex;
        flex-direction:column;
        height:calc(100vh - 150px);
        overflow:hidden;
    }

    .terminal-head{
        background:var(--primary);
        color:#fff;
        border-radius:14px;
        padding:12px 14px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:10px;
        font-size:14px;
        font-weight:800;
        letter-spacing:.05em;
        margin-bottom:10px;
        flex-shrink:0;
    }

    .terminal-total{
        font-size:18px;
        font-weight:800;
    }

    .cart-workspace{
        flex:1;
        min-height:0;
        display:grid;
        grid-template-columns:minmax(0,1fr) 350px;
        gap:10px;
    }

    .cart-board,
    .tool-card,
    .display-card,
    .customer-mini,
    .totals{
        background:#fff;
        border:1px solid var(--line);
        border-radius:14px;
        box-shadow:0 6px 16px rgba(102,126,234,.06);
    }

    .cart-board{
        padding:10px;
        display:flex;
        flex-direction:column;
        min-height:0;
    }

    .cart-board-title{
        font-size:13px;
        font-weight:800;
        color:var(--text);
        margin-bottom:10px;
        letter-spacing:.06em;
    }

    .cart-items-wrap{
        flex:1;
        min-height:140px;
        overflow-y:auto;
    }

    .cart-table{
        width:100%;
        border-collapse:collapse;
        table-layout:fixed;
    }

    .cart-table th{
        text-align:left;
        padding:8px 4px;
        color:var(--text);
        font-weight:700;
        font-size:11px;
        border-bottom:1px solid #e8ecff;
    }

    .cart-table td{
        padding:8px 4px;
        border-bottom:1px solid #f0f3ff;
        vertical-align:middle;
        font-size:13px;
        color:var(--text);
    }

    .cart-table th:nth-child(1),
    .cart-table td:nth-child(1){ width:40%; }

    .cart-table th:nth-child(2),
    .cart-table td:nth-child(2){ width:24%; }

    .cart-table th:nth-child(3),
    .cart-table td:nth-child(3){ width:18%; }

    .cart-table th:nth-child(4),
    .cart-table td:nth-child(4){
        width:18%;
        text-align:right;
    }

    .cart-product-name{
        font-weight:700;
        color:var(--text);
        display:block;
        line-height:1.3;
    }

    .cart-product-sku{
        font-size:11px;
        color:var(--muted);
        display:block;
        margin-top:2px;
        word-break:break-all;
    }

    .cart-qty-input{
        width:68px;
        padding:4px 6px;
        border:1px solid var(--line);
        border-radius:7px;
        text-align:center;
        font-weight:600;
        color:var(--text);
    }

    .cart-remove{
        color:var(--danger);
        cursor:pointer;
        margin-left:8px;
        font-size:13px;
    }

    .cart-price{
        font-weight:700;
        color:var(--primary-dark);
        font-size:12px;
    }

    .side-terminal{
        display:flex;
        flex-direction:column;
        gap:8px;
        min-height:0;
    }

    .tool-card,
    .display-card,
    .customer-mini,
    .totals{
        padding:10px;
    }

    .tool-card label,
    .display-card label,
    .customer-mini label{
        display:block;
        font-size:11px;
        font-weight:800;
        color:var(--text);
        margin-bottom:6px;
        letter-spacing:.05em;
    }

    .customer-mini .form-select,
    .display-card .form-control,
    .tool-card .form-control,
    .tool-card .form-select,
    .cart-note textarea{
        border-radius:10px;
        border:1px solid var(--line);
        box-shadow:none;
        color:var(--text);
    }

    .display-card .form-control{
        font-size:18px;
        font-weight:800;
        text-align:right;
        height:48px;
    }

    .change-amount{
        font-size:13px;
        font-weight:700;
        color:var(--success);
        margin-top:6px;
    }

    .pos-keypad{
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:6px;
    }

    .key-btn{
        height:44px;
        border:none;
        border-radius:8px;
        background:var(--primary);
        color:#fff;
        font-size:15px;
        font-weight:800;
        transition:.15s ease;
    }

    .key-btn:hover{
        background:var(--primary-dark);
        transform:translateY(-1px);
    }

    .key-btn.danger{
        background:var(--danger);
    }

    .payment-methods{
        display:grid;
        grid-template-columns:1fr;
        gap:6px;
    }

    .func-btn{
        background:#fff;
        border:1px solid var(--line);
        border-radius:10px;
        padding:10px;
        font-size:12px;
        font-weight:800;
        color:var(--text);
        cursor:pointer;
        transition:.15s ease;
        text-align:center;
        user-select:none;
    }

    .func-btn:hover{
        border-color:var(--primary);
        background:#eef1ff;
    }

    .func-btn.active{
        background:var(--primary);
        color:#fff;
        border-color:var(--primary);
    }

    .func-btn i{
        margin-right:5px;
    }

    .totals{
        margin-top:2px;
    }

    .total-row{
        display:flex;
        justify-content:space-between;
        gap:8px;
        padding:4px 0;
        font-size:13px;
        color:var(--text);
    }

    .total-row.final{
        font-size:18px;
        font-weight:800;
        color:var(--primary-dark);
        border-top:1px solid var(--line);
        padding-top:8px;
        margin-top:4px;
    }

    .tool-panels{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:10px;
        margin-top:10px;
        flex-shrink:0;
    }

    .discount-inline{
        display:grid;
        grid-template-columns:90px 1fr 42px;
        gap:6px;
        align-items:center;
    }

    .discount-info{
        margin-top:6px;
        font-size:12px;
        color:var(--success);
        font-weight:700;
    }

    .cart-note textarea{
        resize:none;
        min-height:70px;
    }

    .function-dock{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:8px;
        margin-top:10px;
        flex-shrink:0;
    }

    .dock-btn{
        border:none;
        background:#eef1ff;
        color:var(--text);
        border-radius:10px;
        min-height:48px;
        font-size:12px;
        font-weight:800;
        letter-spacing:.02em;
        transition:.15s ease;
        border:1px solid var(--line);
    }

    .dock-btn:hover{
        border-color:var(--primary);
        background:#e3e8ff;
    }

    .dock-btn-primary{
        background:var(--primary);
        color:#fff;
        border-color:var(--primary);
    }

    .dock-btn-primary:hover{
        background:var(--primary-dark);
    }

    .dock-btn-primary:disabled{
        opacity:.55;
        cursor:not-allowed;
    }

    .shift-info{
        background:#eef1ff;
        border-radius:12px;
        padding:12px 15px;
        margin:0 auto 14px;
        display:flex;
        align-items:center;
        gap:12px;
        border-left:4px solid var(--primary);
        max-width:1520px;
    }

    .shift-info i{
        color:var(--primary);
        font-size:20px;
    }

    .shift-text{
        flex:1;
        font-size:14px;
        color:var(--text);
    }

    .shift-actions{
        display:flex;
        gap:10px;
    }

    .modal-content{ border-radius:14px; }
    .modal-header{
        background:var(--primary);
        color:#fff;
        border-radius:14px 14px 0 0;
    }
    .modal-header .btn-close{ filter:brightness(0) invert(1); }

    @media (max-width: 1550px){
        .pos-container{
            grid-template-columns:1fr;
            max-width:100%;
        }

        .products-section,
        .cart-section{
            width:100%;
            height:auto;
        }

        .cart-workspace{
            grid-template-columns:1fr;
        }

        .side-terminal{
            order:-1;
        }
    }

    @media (max-width: 768px){
        .tool-panels,
        .function-dock{
            grid-template-columns:1fr 1fr;
        }
    }
</style>
<?php $extraCss = ''; ?>

<!-- Page Content -->
<div class="pos-page">
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
    <style>.pos-container { display:none; }</style>
<?php else: ?>
    <?php
        $subtotal = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $subtotal += (float)$item['total'];
            }
        }
    ?>
    <div class="shift-info">
        <i class="fas fa-check-circle text-success"></i>
        <div class="shift-text">
            <strong>Smena ochiq</strong> |
            Ochilgan: <?= date('d.m.Y H:i', strtotime($smena['ochilgan_vaqt'])) ?> |
            Boshlang'ich naqd: <?= number_format($smena['ochilish_naqd'], 0, ',', ' ') ?> so'm
        </div>
        <div class="shift-actions">
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#closeShiftModal">
                <i class="fas fa-stop"></i> Smena yopish
            </button>
        </div>
    </div>

    <div class="pos-container">
        <div class="products-section">
            <div class="products-toolbar">
                <div class="toolbar-head">
                    <div>
                        <div class="toolbar-title">MAHSULOTLAR</div>
                        <div class="toolbar-subtitle">Qidirish yoki mahsulot ustiga bosib savatga qo‘shing</div>
                    </div>
                </div>

                <div class="search-box">
                    <div class="input-group">
                        <input type="text" id="searchProduct" class="form-control" placeholder="Mahsulot nomi yoki shtrix kod bo‘yicha qidirish" autofocus>
                        <button class="btn" type="button" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="products-scroll">
                <div class="products-grid" id="productsGrid">
                    <?php foreach ($products as $product): ?>
                        <?php if ($product['faol']): ?>
                            <div class="product-card <?= $product['miqdor'] <= 0 ? 'out-of-stock' : '' ?>"
                                data-id="<?= $product['id'] ?>"
                                data-name="<?= htmlspecialchars($product['nomi']) ?>"
                                data-price="<?= $product['sotish_narxi'] ?>"
                                data-stock="<?= $product['miqdor'] ?>">
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
        </div>

        <div class="cart-section" id="cartSection">
            <div class="terminal-head">
                <span>SAVAT / TO‘LOV</span>
                <span class="terminal-total" id="terminalTotal"><?= number_format($subtotal, 0, ',', ' ') ?> so'm</span>
            </div>

            <div class="cart-workspace">
                <div class="cart-board">
                    <div class="cart-board-title">SAVATDAGI MAHSULOTLAR</div>

                    <div class="cart-items-wrap">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>MAHSULOT</th>
                                    <th>MIQDOR</th>
                                    <th>NARXI</th>
                                    <th>JAMI</th>
                                </tr>
                            </thead>
                            <tbody id="cartItems">
                                <?php if (empty($cart)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Savat bo‘sh</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($cart as $item): ?>
                                        <tr data-id="<?= $item['id'] ?>">
                                            <td>
                                                <span class="cart-product-name"><?= htmlspecialchars($item['name']) ?></span>
                                                <span class="cart-product-sku"><?= htmlspecialchars($item['barcode'] ?? '') ?></span>
                                            </td>
                                            <td>
                                                <input
                                                    type="number"
                                                    class="cart-qty-input"
                                                    value="<?= $item['quantity'] ?>"
                                                    min="0"
                                                    max="<?= $item['stock'] ?>"
                                                    step="any"
                                                    onchange="updateCartItem(<?= $item['id'] ?>, this.value)"
                                                >
                                                <i class="fas fa-trash cart-remove" onclick="removeFromCart(<?= $item['id'] ?>)"></i>
                                            </td>
                                            <td class="cart-price"><?= number_format($item['price'], 0, ',', ' ') ?> so'm</td>
                                            <td class="cart-price"><?= number_format($item['total'], 0, ',', ' ') ?> so'm</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="side-terminal">
                    <div class="customer-mini">
                        <label for="customerId">MIJOZ</label>
                        <select id="customerId" class="form-select">
                            <option value="">Mijoz tanlash</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['id'] ?>">
                                    <?= htmlspecialchars($customer['fio']) ?> - <?= htmlspecialchars($customer['telefon'] ?? 'Tel yo‘q') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="display-card">
                        <label for="paidAmount">TO‘LANGAN SUMMA</label>
                        <input
                            type="text"
                            id="paidAmount"
                            class="form-control"
                            placeholder="0"
                            value="<?= $subtotal ?>"
                            onkeyup="this.value = this.value.replace(/[^0-9\.]/g, ''); calculateChange()"
                        >
                        <div class="change-amount" id="changeAmount" style="display:none;">
                            Qaytim: <span id="changeValue">0</span>
                        </div>
                    </div>

                    <div class="pos-keypad">
                        <button type="button" class="key-btn" onclick="keypadPress('7')">7</button>
                        <button type="button" class="key-btn" onclick="keypadPress('8')">8</button>
                        <button type="button" class="key-btn" onclick="keypadPress('9')">9</button>

                        <button type="button" class="key-btn" onclick="keypadPress('4')">4</button>
                        <button type="button" class="key-btn" onclick="keypadPress('5')">5</button>
                        <button type="button" class="key-btn" onclick="keypadPress('6')">6</button>

                        <button type="button" class="key-btn" onclick="keypadPress('1')">1</button>
                        <button type="button" class="key-btn" onclick="keypadPress('2')">2</button>
                        <button type="button" class="key-btn" onclick="keypadPress('3')">3</button>

                        <button type="button" class="key-btn" onclick="keypadPress('0')">0</button>
                        <button type="button" class="key-btn danger" onclick="keypadBackspace()">⌫</button>
                        <button type="button" class="key-btn" onclick="checkout()">↵</button>
                    </div>

                    <div class="payment-methods">
                        <span class="func-btn payment-method active" data-method="NAQD" onclick="selectPaymentMethod('NAQD')">
                            <i class="fas fa-money-bill-wave"></i> NAQD
                        </span>
                        <span class="func-btn payment-method" data-method="KARTA" onclick="selectPaymentMethod('KARTA')">
                            <i class="fas fa-credit-card"></i> KARTA
                        </span>
                        <span class="func-btn payment-method" data-method="ARALASH" onclick="selectPaymentMethod('ARALASH')">
                            <i class="fas fa-combine"></i> ARALASH
                        </span>
                    </div>

                    <div class="totals">
                        <div class="total-row">
                            <span>Sub Total</span>
                            <span id="subtotal"><?= number_format($subtotal, 0, ',', ' ') ?> so'm</span>
                        </div>
                        <div class="total-row" id="discountRow" style="display:none;">
                            <span>Chegirma</span>
                            <span class="text-danger" id="discountDisplay">-0 so'm</span>
                        </div>
                        <div class="total-row final">
                            <span>JAMI</span>
                            <span id="cartTotal"><?= number_format($subtotal, 0, ',', ' ') ?> so'm</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tool-panels">
                <div class="tool-card discount-section">
                    <label>CHEGIRMA</label>
                    <div class="discount-inline">
                        <select id="discountType" class="form-select" onchange="toggleDiscountType()">
                            <option value="fixed">So'm</option>
                            <option value="percent">Foiz</option>
                        </select>

                        <input
                            type="text"
                            id="discountValue"
                            class="form-control"
                            placeholder="0"
                            value="0"
                            onfocus="setKeypadTarget('discountValue')"
                            onkeyup="this.value = this.value.replace(/[^0-9\.]/g, ''); calculateDiscount()"
                        >

                        <button class="btn btn-outline-danger" type="button" onclick="clearDiscount()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div id="discountInfo" class="discount-info" style="display:none;">
                        Chegirma: <span id="discountAmount">0</span> so'm
                    </div>
                </div>

                <!-- <div class="tool-card cart-note">
                    <label for="note">IZOH</label>
                    <textarea id="note" class="form-control" rows="2" placeholder="Izoh (ixtiyoriy)"></textarea>
                </div> -->
            </div>

            <div class="function-dock">
                <button type="button" class="dock-btn" >
                    <a href="/new-pos/returns" <?= strpos($_SERVER['REQUEST_URI'], 'returns') !== false ? 'active' : '' ?>">
                        <i class="fas fa-undo-alt"></i> <span>Qaytarish</span>
                    </a>
                </button>
                <button type="button" class="dock-btn" onclick="clearCart()">TOZALASH</button>
                <!-- <button type="button" class="dock-btn" onclick="document.getElementById('note')?.focus()">IZOH</button> -->
                <button type="button" class="dock-btn dock-btn-primary" id="checkoutBtn" onclick="checkout()" <?= empty($cart) ? 'disabled' : '' ?>>
                    CONFIRM
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>

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
                        <input type="text" name="opening_cash" class="form-control" value="0" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" required>
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
                        <input type="text" name="closing_cash" class="form-control" value="0" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" required>
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
let subtotal = <?= isset($subtotal) ? (float)$subtotal : 0 ?>;
let keypadTargetId = 'paidAmount';
const csrfToken = '<?= csrf_token() ?>';

function byId(id) {
    return document.getElementById(id);
}

function apiPath(endpoint) {
    return '/new-pos' + endpoint;
}

function parseNumeric(value) {
    return parseFloat(String(value ?? '').replace(/[^0-9.]/g, '')) || 0;
}

function numberFormat(n) {
    return Number(n || 0).toLocaleString('uz-UZ');
}

function escapeHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, function (m) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[m];
    });
}

function setCheckoutDisabled(disabled) {
    const btn = byId('checkoutBtn');
    if (btn) btn.disabled = !!disabled;
}

function setTerminalTotalValue(value) {
    const el = byId('terminalTotal');
    if (el) {
        el.textContent = numberFormat(value || 0) + ' so‘m';
    }
}

function getCartTotalValue() {
    const el = byId('cartTotal');
    return el ? parseNumeric(el.textContent) : 0;
}

function setKeypadTarget(id) {
    keypadTargetId = id;
}

function getKeypadInput() {
    return byId(keypadTargetId) || byId('paidAmount');
}

function keypadPress(val) {
    const input = getKeypadInput();
    if (!input) return;

    let current = String(input.value || '').replace(/[^0-9.]/g, '');
    if (current === '0') current = '';
    input.value = current + val;

    if (input.id === 'discountValue') {
        calculateDiscount();
    } else {
        calculateChange();
    }

    input.focus();
}

function keypadBackspace() {
    const input = getKeypadInput();
    if (!input) return;

    let current = String(input.value || '').replace(/[^0-9.]/g, '');
    input.value = current.slice(0, -1);

    if (input.id === 'discountValue') {
        calculateDiscount();
    } else {
        calculateChange();
    }

    input.focus();
}

function addToCart(productId) {
    const product = document.querySelector(`.product-card[data-id="${productId}"]`);
    if (!product) {
        alert('Mahsulot topilmadi');
        return;
    }

    if (product.classList.contains('out-of-stock')) {
        alert('Mahsulot omborda mavjud emas!');
        return;
    }

    const fd = new FormData();
    fd.append('csrf_token', csrfToken);
    fd.append('product_id', productId);
    fd.append('quantity', 1);

    fetch(apiPath('/pos/add-to-cart'), {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(t => {
                throw new Error('HTTP ' + response.status + ': ' + t);
            });
        }
        const ct = response.headers.get('content-type') || '';
        if (ct.includes('application/json')) return response.json();
        return response.text().then(t => JSON.parse(t));
    })
    .then(data => {
        if (data && data.success) {
            renderCart(data);
            const searchInput = byId('searchProduct');
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }
        } else if (data && data.error) {
            alert(data.error);
        } else {
            location.reload();
        }
    })
    .catch(err => {
        console.error('addToCart error:', err);
        alert('Xatolik yuz berdi: ' + (err.message || 'Iltimos, qayta urinib ko‘ring.'));
    });
}

function updateCartItem(productId, quantity) {
    quantity = parseFloat(quantity);

    if (isNaN(quantity)) {
        alert('Iltimos, to‘g‘ri miqdor kiriting');
        refreshCart();
        return;
    }

    if (quantity <= 0) {
        removeFromCart(productId);
        return;
    }

    fetch(apiPath('/pos/update-cart'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity,
            csrf_token: csrfToken
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            refreshCart();
        } else {
            alert(data.error || 'Xatolik yuz berdi');
        }
    })
    .catch(err => {
        console.error('updateCartItem error:', err);
        alert('Xatolik yuz berdi');
    });
}

function removeFromCart(productId) {
    if (!confirm('Mahsulotni savatdan o‘chirishni xohlaysizmi?')) return;

    const fd = new FormData();
    fd.append('csrf_token', csrfToken);
    fd.append('product_id', productId);

    fetch(apiPath('/pos/remove-from-cart'), {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderCart(data);
        } else {
            alert(data.error || 'Xatolik yuz berdi');
        }
    })
    .catch(err => {
        console.error('removeFromCart error:', err);
        alert('Xatolik yuz berdi');
    });
}

function clearCart() {
    if (!confirm('Savatni tozalashni xohlaysizmi?')) return;

    const fd = new FormData();
    fd.append('csrf_token', csrfToken);

    fetch(apiPath('/pos/clear-cart'), {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(t => {
                throw new Error('HTTP ' + response.status + ': ' + t);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            renderCart(data);
        } else {
            location.reload();
        }
    })
    .catch(err => {
        console.error('clearCart error:', err);
        alert('Xatolik yuz berdi: ' + (err.message || ''));
    });
}

function refreshCart() {
    fetch(apiPath('/pos/view-cart'), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data) {
            renderCart({
                items: data.items || [],
                total: data.total || 0
            });
        }
    })
    .catch(err => {
        console.error('refreshCart error:', err);
    });
}

function renderCart(data) {
    const items = Array.isArray(data?.items) ? data.items : (Array.isArray(data) ? data : []);
    const tbody = byId('cartItems');
    const subtotalEl = byId('subtotal');
    const cartTotalEl = byId('cartTotal');
    const paidAmountEl = byId('paidAmount');

    if (!tbody) return;

    tbody.innerHTML = '';

    if (!items.length) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4">Savat bo‘sh</td></tr>';
        if (subtotalEl) subtotalEl.textContent = '0 so‘m';
        if (cartTotalEl) cartTotalEl.textContent = '0 so‘m';
        if (paidAmountEl) paidAmountEl.value = '0';

        subtotal = 0;
        setCheckoutDisabled(true);
        calculateDiscount();
        setTerminalTotalValue(0);
        return;
    }

    let sum = 0;

    items.forEach(item => {
        const qty = parseFloat(item.quantity || 0);
        const price = parseFloat(item.price || 0);
        const total = parseFloat(item.total || 0);
        const stock = parseFloat(item.stock || 0);
        const unit = String(item.unit || '').toLowerCase();
        const step = (unit === 'kg' || unit === 'litr' || unit === 'l') ? '0.001' : '1';

        sum += total;

        const tr = document.createElement('tr');
        tr.setAttribute('data-id', item.id);
        tr.innerHTML = `
            <td>
                <span class="cart-product-name">${escapeHtml(item.name)}</span>
                <span class="cart-product-sku">${escapeHtml(item.barcode || '')}</span>
            </td>
            <td>
                <input
                    type="number"
                    class="cart-qty-input"
                    value="${qty}"
                    min="0"
                    max="${stock}"
                    step="${step}"
                    onchange="updateCartItem(${item.id}, this.value)"
                >
                <i class="fas fa-trash cart-remove" onclick="removeFromCart(${item.id})"></i>
            </td>
            <td class="cart-price">${numberFormat(price)} so‘m</td>
            <td class="cart-price">${numberFormat(total)} so‘m</td>
        `;
        tbody.appendChild(tr);
    });

    subtotal = sum;

    if (subtotalEl) subtotalEl.textContent = numberFormat(subtotal) + ' so‘m';
    if (cartTotalEl) cartTotalEl.textContent = numberFormat(subtotal) + ' so‘m';
    if (paidAmountEl) paidAmountEl.value = subtotal;

    setCheckoutDisabled(false);
    calculateDiscount();
    setTerminalTotalValue(getCartTotalValue());
}

function calculateDiscount() {
    const discountTypeEl = byId('discountType');
    const discountValueEl = byId('discountValue');
    const discountRowEl = byId('discountRow');
    const discountDisplayEl = byId('discountDisplay');
    const discountInfoEl = byId('discountInfo');
    const discountAmountEl = byId('discountAmount');
    const cartTotalEl = byId('cartTotal');
    const paidAmountEl = byId('paidAmount');

    if (!discountTypeEl || !discountValueEl || !cartTotalEl) return;

    const discountType = discountTypeEl.value;
    const discountValue = parseNumeric(discountValueEl.value);

    let discountAmount = 0;

    if (discountType === 'percent' && discountValue > 0) {
        discountAmount = (subtotal * discountValue) / 100;
    } else if (discountType === 'fixed' && discountValue > 0) {
        discountAmount = discountValue;
    }

    if (discountAmount > subtotal) discountAmount = subtotal;

    const total = subtotal - discountAmount;

    if (discountAmount > 0) {
        if (discountRowEl) discountRowEl.style.display = 'flex';
        if (discountDisplayEl) discountDisplayEl.textContent = '-' + numberFormat(discountAmount) + ' so‘m';
        if (discountInfoEl) discountInfoEl.style.display = 'block';
        if (discountAmountEl) discountAmountEl.textContent = numberFormat(discountAmount);
    } else {
        if (discountRowEl) discountRowEl.style.display = 'none';
        if (discountInfoEl) discountInfoEl.style.display = 'none';
    }

    cartTotalEl.textContent = numberFormat(total) + ' so‘m';
    if (paidAmountEl) paidAmountEl.value = total;

    calculateChange();
    setTerminalTotalValue(total);
}

function toggleDiscountType() {
    const discountValueEl = byId('discountValue');
    if (discountValueEl) discountValueEl.value = '0';
    calculateDiscount();
}

function clearDiscount() {
    const discountTypeEl = byId('discountType');
    const discountValueEl = byId('discountValue');
    if (discountTypeEl) discountTypeEl.value = 'fixed';
    if (discountValueEl) discountValueEl.value = '0';
    calculateDiscount();
}

function selectPaymentMethod(method) {
    selectedPaymentMethod = method;

    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('active');
    });

    const activeEl = document.querySelector(`.payment-method[data-method="${method}"]`);
    if (activeEl) activeEl.classList.add('active');
}

function calculateChange() {
    const total = getCartTotalValue();
    const paidAmountEl = byId('paidAmount');
    const changeValueEl = byId('changeValue');
    const changeAmountEl = byId('changeAmount');

    if (!paidAmountEl || !changeValueEl || !changeAmountEl) return;

    const paid = parseNumeric(paidAmountEl.value);

    if (paid >= total && total > 0) {
        const change = paid - total;
        changeValueEl.textContent = numberFormat(change) + ' so‘m';
        changeAmountEl.style.display = 'block';
    } else {
        changeAmountEl.style.display = 'none';
    }
}

function appendHidden(form, name, value) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value ?? '';
    form.appendChild(input);
}

function checkout() {
    const cartRows = document.querySelectorAll('#cartItems tr[data-id]');
    if (!cartRows.length || subtotal <= 0) {
        alert('Savat bo‘sh');
        return;
    }

    const total = getCartTotalValue();
    const paid = parseNumeric(byId('paidAmount')?.value || 0);
    const customerId = byId('customerId')?.value || '';
    const note = byId('note')?.value || '';
    const discountType = byId('discountType')?.value || 'fixed';
    const discountValue = byId('discountValue')?.value || '0';

    if (paid <= 0 && customerId) {
        if (!confirm('To‘langan summa 0. Nasiya qilishni xohlaysizmi?')) return;
    }

    if (paid < total && !customerId) {
        alert('To‘lov yetarli emas. Nasiya uchun mijoz tanlang.');
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = apiPath('/pos/checkout');

    appendHidden(form, 'csrf_token', csrfToken);
    appendHidden(form, 'payment_method', selectedPaymentMethod);
    appendHidden(form, 'paid_amount', paid);
    appendHidden(form, 'customer_id', customerId);
    appendHidden(form, 'note', note);
    appendHidden(form, 'discount_type', discountType);
    appendHidden(form, 'discount_value', discountValue);

    document.body.appendChild(form);
    form.submit();
}

function searchProducts() {
    const searchInput = byId('searchProduct');
    const productsGrid = byId('productsGrid');
    if (!searchInput || !productsGrid) return;

    const term = searchInput.value.trim().toLowerCase();
    const products = document.querySelectorAll('.product-card');
    let visible = 0;

    products.forEach(card => {
        const name = String(card.dataset.name || '').toLowerCase();
        const barcode = String(card.querySelector('.barcode')?.textContent || '').toLowerCase();
        const match = !term || name.includes(term) || barcode.includes(term);

        card.style.display = match ? 'flex' : 'none';
        if (match) visible++;
    });

    const oldMsg = byId('emptySearchMessage');
    if (oldMsg) oldMsg.remove();

    if (visible === 0) {
        const msg = document.createElement('div');
        msg.id = 'emptySearchMessage';
        msg.className = 'empty-products';
        msg.innerHTML = '<i class="fas fa-search"></i><p>Hech qanday mahsulot topilmadi</p>';
        productsGrid.appendChild(msg);
    }
}

function isTypingElement(el) {
    if (!el) return false;
    const tag = el.tagName;
    return tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || el.isContentEditable;
}

document.addEventListener('DOMContentLoaded', function () {
    const searchBtn = byId('searchBtn');
    const searchInput = byId('searchProduct');
    const paidAmountEl = byId('paidAmount');
    const discountValueEl = byId('discountValue');

    if (searchBtn) {
        searchBtn.addEventListener('click', searchProducts);
    }

    if (searchInput) {
        searchInput.addEventListener('input', searchProducts);
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') searchProducts();
        });
        searchInput.focus();
    }

    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function(e) {
            e.stopPropagation();

            if (this.classList.contains('out-of-stock')) {
                alert('Mahsulot omborda mavjud emas!');
                return;
            }

            const id = this.dataset.id;
            if (id) addToCart(id);
        });
    });

    if (paidAmountEl) {
        paidAmountEl.addEventListener('focus', function () {
            setKeypadTarget('paidAmount');
        });
    }

    if (discountValueEl) {
        discountValueEl.addEventListener('focus', function () {
            setKeypadTarget('discountValue');
        });
    }

    document.addEventListener('keydown', function (e) {
        const typing = isTypingElement(document.activeElement);

        if (e.key === 'F2') {
            e.preventDefault();
            if (searchInput) searchInput.focus();
            return;
        }

        if (e.key === 'F3') {
            e.preventDefault();
            clearCart();
            return;
        }

        if (e.key === 'F4') {
            e.preventDefault();
            checkout();
            return;
        }

        if (typing) return;

        if (e.key === '1') selectPaymentMethod('NAQD');
        if (e.key === '2') selectPaymentMethod('KARTA');
        if (e.key === '3') selectPaymentMethod('ARALASH');
    });

    let barcodeBuffer = '';
    let barcodeTimeout;

    document.addEventListener('keypress', function (e) {
        if (isTypingElement(document.activeElement)) return;
        if (e.key.length !== 1) return;

        barcodeBuffer += e.key;
        clearTimeout(barcodeTimeout);

        barcodeTimeout = setTimeout(() => {
            if (barcodeBuffer.length > 3) {
                const products = document.querySelectorAll('.product-card');
                let found = false;

                products.forEach(p => {
                    const barcode = p.querySelector('.barcode')?.textContent || '';
                    if (barcode === barcodeBuffer) {
                        found = true;
                        if (!p.classList.contains('out-of-stock')) {
                            if (searchInput) {
                                searchInput.value = '';
                                searchInput.focus();
                            }
                            addToCart(p.dataset.id);
                        }
                    }
                });

                if (!found) {
                    alert('Mahsulot topilmadi: ' + barcodeBuffer);
                }
            }

            barcodeBuffer = '';
        }, 100);
    });

    calculateDiscount();
    calculateChange();
    setTerminalTotalValue(getCartTotalValue());
    setCheckoutDisabled(subtotal <= 0);
});
</script>
<?php $extraJs = ob_get_clean(); ?>