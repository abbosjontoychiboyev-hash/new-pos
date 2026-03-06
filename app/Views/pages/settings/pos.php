<!-- Page Title -->
<?php $title = 'POS sozlamalari'; ?>

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
    
    .receipt-preview {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
        font-family: 'Courier New', monospace;
        border: 1px dashed #667eea;
    }
    
    .receipt-preview .header {
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .receipt-preview .line {
        border-top: 1px dashed #999;
        margin: 10px 0;
    }
    
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .tab-pane {
        padding: 20px 0;
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
        
        .nav-tabs {
            flex-wrap: wrap;
        }
        
        .nav-tabs .nav-item {
            width: 100%;
            margin-bottom: 5px;
        }
        
        .nav-tabs .nav-link {
            width: 100%;
            text-align: center;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Update receipt preview
    function updateReceiptPreview() {
        const header = document.getElementById('receiptHeader').value;
        const footer = document.getElementById('receiptFooter').value;
        
        let preview = '';
        
        // Header
        if (header) {
            preview += header.replace(/\\n/g, '<br>') + '<br>';
        }
        
        // Standard content
        preview += '<div class="line"></div>';
        preview += '<div>Sana: <?= date('d.m.Y H:i') ?></div>';
        preview += '<div>Kassir: Admin</div>';
        preview += '<div class="line"></div>';
        preview += '<div>Mahsulot 1     2 x 10,000</div>';
        preview += '<div>Mahsulot 2     1 x 15,000</div>';
        preview += '<div class="line"></div>';
        preview += '<div>JAMI:         35,000 so\'m</div>';
        preview += '<div>To\'landi:     35,000 so\'m</div>';
        preview += '<div class="line"></div>';
        
        // Footer
        if (footer) {
            preview += footer.replace(/\\n/g, '<br>');
        }
        
        document.getElementById('receiptPreview').innerHTML = preview;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const headerInput = document.getElementById('receiptHeader');
        const footerInput = document.getElementById('receiptFooter');
        
        if (headerInput) headerInput.addEventListener('input', updateReceiptPreview);
        if (footerInput) footerInput.addEventListener('input', updateReceiptPreview);
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<div class="form-card">
    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-shopping-cart"></i> POS va chek sozlamalari
        </div>
        <a href="/new-pos/settings" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>
    
    <!-- Tabs -->
    <ul class="nav nav-tabs" id="posTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">Umumiy</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="receipt-tab" data-bs-toggle="tab" data-bs-target="#receipt" type="button" role="tab">Chek formati</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">To'lov usullari</button>
        </li>
    </ul>
    
    <form method="POST" action="/new-pos/settings/pos/save">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <!-- Tab Content -->
        <div class="tab-content" id="posTabsContent">
            <!-- Umumiy sozlamalar -->
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Standart to'lov usuli</label>
                        <select name="default_payment_method" class="form-select">
                            <option value="NAQD" <?= ($pos['default_payment_method'] ?? 'NAQD') == 'NAQD' ? 'selected' : '' ?>>Naqd pul</option>
                            <option value="KARTA" <?= ($pos['default_payment_method'] ?? '') == 'KARTA' ? 'selected' : '' ?>>Plastik karta</option>
                            <option value="ARALASH" <?= ($pos['default_payment_method'] ?? '') == 'ARALASH' ? 'selected' : '' ?>>Aralash</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Qarz berish</label>
                        <select name="allow_debt" class="form-select">
                            <option value="1" <?= ($pos['allow_debt'] ?? 1) == 1 ? 'selected' : '' ?>>Ruxsat berilgan</option>
                            <option value="0" <?= ($pos['allow_debt'] ?? 1) == 0 ? 'selected' : '' ?>>Ruxsat berilmagan</option>
                        </select>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="auto_print_receipt" id="auto_print" value="1" <?= ($pos['auto_print_receipt'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="auto_print">
                                Savdo yakunlanganda avtomatik chek chop etish
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="show_customer_on_receipt" id="show_customer" value="1" <?= ($pos['show_customer_on_receipt'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="show_customer">
                                Chekda mijoz ma'lumotlarini ko'rsatish
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Chek formati -->
            <div class="tab-pane fade" id="receipt" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Chek sarlavhasi</label>
                        <textarea name="receipt_header" class="form-control" rows="3" id="receiptHeader"><?= htmlspecialchars($pos['receipt_header'] ?? 'POS MAGAZIN\nSavdo cheki\n') ?></textarea>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Chek altbilgisi</label>
                        <textarea name="receipt_footer" class="form-control" rows="3" id="receiptFooter"><?= htmlspecialchars($pos['receipt_footer'] ?? 'Savdo uchun rahmat!\nTel: +998 (78) 123-45-67') ?></textarea>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Chek namunasi:</label>
                        <div class="receipt-preview" id="receiptPreview">
                            <div class="header">POS MAGAZIN</div>
                            <div>Savdo cheki</div>
                            <div class="line"></div>
                            <div>Sana: <?= date('d.m.Y H:i') ?></div>
                            <div>Kassir: Admin</div>
                            <div class="line"></div>
                            <div>Mahsulot 1     2 x 10,000</div>
                            <div>Mahsulot 2     1 x 15,000</div>
                            <div class="line"></div>
                            <div>JAMI:         35,000 so'm</div>
                            <div>To'landi:     35,000 so'm</div>
                            <div class="line"></div>
                            <div>Savdo uchun rahmat!</div>
                            <div>Tel: +998 (78) 123-45-67</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- To'lov usullari -->
            <div class="tab-pane fade" id="payment" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="payment_naqd" id="payment_naqd" value="1" <?= ($pos['payment_naqd'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="payment_naqd">Naqd pul</label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="payment_karta" id="payment_karta" value="1" <?= ($pos['payment_karta'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="payment_karta">Plastik karta</label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="payment_aralash" id="payment_aralash" value="1" <?= ($pos['payment_aralash'] ?? 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="payment_aralash">Aralash to'lov</label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="payment_transfer" id="payment_transfer" value="1" <?= ($pos['payment_transfer'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="payment_transfer">Pul o'tkazma</label>
                        </div>
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