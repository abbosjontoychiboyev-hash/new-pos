<!-- Page Title -->
<?php $title = 'Qaytarish detallari - ' . ($sale['chek_raqami'] ?? ''); ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .sale-info {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }
    
    .info-item {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .info-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }
    
    .info-value {
        font-weight: 600;
        color: #333;
    }
    
    .warning-box {
        background: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
    }
    
    .table-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .table-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .table-title i {
        color: #667eea;
        margin-right: 10px;
    }
    
    .table th {
        background: #f8f9fa;
    }
    
    .return-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
    .return-quantity {
        width: 80px;
        padding: 5px;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        text-align: center;
    }
    
    .return-quantity:focus {
        border-color: #667eea;
        outline: none;
    }
    
    .btn-return {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-return:hover {
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
    
    .total-return {
        font-size: 18px;
        font-weight: 600;
        color: #dc3545;
        margin-top: 15px;
        text-align: right;
    }
    
    @media (max-width: 992px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .table-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
        
        .table th, .table td {
            white-space: nowrap;
        }
        
        .d-flex.justify-content-end {
            flex-direction: column;
        }
        
        .btn-return, .btn-cancel {
            width: 100%;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    function toggleReturnType() {
        const returnType = document.querySelector('input[name="return_type"]:checked').value;
        const checkboxes = document.querySelectorAll('.return-checkbox');
        const quantities = document.querySelectorAll('.return-quantity');
        const submitBtn = document.getElementById('submitBtn');
        
        if (returnType === 'full') {
            // To'liq qaytarish - barcha mahsulotlarni tanlash
            checkboxes.forEach(cb => {
                cb.checked = true;
                cb.disabled = true;
            });
            
            quantities.forEach(q => {
                q.disabled = false;
                q.value = q.max;
                q.readOnly = true;
            });
            
            submitBtn.innerHTML = '<i class="fas fa-undo-alt"></i> To\'liq qaytarish';
        } else {
            // Qisman qaytarish
            checkboxes.forEach(cb => {
                cb.checked = false;
                cb.disabled = false;
            });
            
            quantities.forEach(q => {
                q.disabled = true;
                q.value = 0;
                q.readOnly = false;
            });
            
            submitBtn.innerHTML = '<i class="fas fa-undo-alt"></i> Qaytarishni amalga oshirish';
        }
        
        calculateTotal();
    }
    
    function updateRow(checkbox) {
        const row = checkbox.closest('tr');
        const quantityInput = row.querySelector('.return-quantity');
        const price = parseFloat(checkbox.dataset.price);
        const maxQty = parseInt(checkbox.dataset.max);
        
        if (checkbox.checked) {
            quantityInput.disabled = false;
            quantityInput.value = 1;
            if (quantityInput.value > maxQty) quantityInput.value = maxQty;
        } else {
            quantityInput.disabled = true;
            quantityInput.value = 0;
        }
        
        calculateTotal();
    }
    
    function calculateTotal() {
        let total = 0;
        const rows = document.querySelectorAll('.product-row');
        
        rows.forEach(row => {
            const checkbox = row.querySelector('.return-checkbox');
            const quantity = parseInt(row.querySelector('.return-quantity').value) || 0;
            
            if (checkbox && checkbox.checked && quantity > 0) {
                const price = parseFloat(checkbox.dataset.price);
                const itemTotal = price * quantity;
                total += itemTotal;
                
                row.querySelector('.item-total').textContent = itemTotal.toLocaleString() + ' so\'m';
            } else {
                row.querySelector('.item-total').textContent = '0 so\'m';
            }
        });
        
        document.getElementById('totalReturn').innerHTML = 'Jami qaytarish: ' + total.toLocaleString() + ' so\'m';
    }
    
    // Form validation
    document.getElementById('returnForm')?.addEventListener('submit', function(e) {
        const returnType = document.querySelector('input[name="return_type"]:checked').value;
        const reason = document.querySelector('textarea[name="reason"]').value.trim();
        
        if (!reason) {
            e.preventDefault();
            alert('Iltimos, qaytarish sababini kiriting');
            return;
        }
        
        if (returnType === 'partial') {
            const checkboxes = document.querySelectorAll('.return-checkbox:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Hech qanday mahsulot tanlanmagan');
                return;
            }
            
            let hasQuantity = false;
            checkboxes.forEach(cb => {
                const row = cb.closest('tr');
                const qty = parseInt(row.querySelector('.return-quantity').value) || 0;
                if (qty > 0) hasQuantity = true;
            });
            
            if (!hasQuantity) {
                e.preventDefault();
                alert('Qaytarish miqdorini kiriting');
                return;
            }
        }
        
        if (!confirm('Qaytarishni amalga oshirishni tasdiqlaysizmi?')) {
            e.preventDefault();
        }
    });
    
    // Tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltips.length > 0) {
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<!-- Flash Messages -->
<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['flash']['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash']['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['flash']['warning'])): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> <?= $_SESSION['flash']['warning'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash']['warning']); ?>
<?php endif; ?>

<!-- Sale Information -->
<div class="sale-info">
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Chek raqami</div>
            <div class="info-value"><?= $sale['chek_raqami'] ?></div>
        </div>
        <div class="info-item">
            <div class="info-label">Sana</div>
            <div class="info-value"><?= date('d.m.Y H:i', strtotime($sale['sotilgan_vaqt'])) ?></div>
        </div>
        <div class="info-item">
            <div class="info-label">Kassir</div>
            <div class="info-value"><?= htmlspecialchars($sale['kassir_fio'] ?? '-') ?></div>
        </div>
        <div class="info-item">
            <div class="info-label">Mijoz</div>
            <div class="info-value"><?= htmlspecialchars($sale['mijoz_fio'] ?? 'Anonim') ?></div>
        </div>
        <div class="info-item">
            <div class="info-label">Jami summa</div>
            <div class="info-value"><?= number_format($sale['yakuniy_summa'], 0, ',', ' ') ?> so'm</div>
        </div>
        <div class="info-item">
            <div class="info-label">To'langan</div>
            <div class="info-value"><?= number_format($sale['tolangan_summa'], 0, ',', ' ') ?> so'm</div>
        </div>
        <div class="info-item">
            <div class="info-label">Qarz</div>
            <div class="info-value <?= $sale['qarz_summa'] > 0 ? 'text-danger' : '' ?>">
                <?= number_format($sale['qarz_summa'], 0, ',', ' ') ?> so'm
            </div>
        </div>
        <div class="info-item">
            <div class="info-label">To'lov usuli</div>
            <div class="info-value">
                <?php 
                $usul = $sale['tolov_usuli'];
                if ($usul == 'NAQD') echo 'Naqd';
                elseif ($usul == 'KARTA') echo 'Karta';
                elseif ($usul == 'ARALASH') echo 'Aralash';
                else echo $usul;
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Warning for old sales -->
<?php 
$saleTime = strtotime($sale['sotilgan_vaqt']);
$daysDiff = floor((time() - $saleTime) / (60 * 60 * 24));
if ($daysDiff > 7): 
?>
<div class="warning-box">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Diqqat!</strong> Bu savdo <?= $daysDiff ?> kun oldin qilingan. Qaytarish muddati o'tgan bo'lishi mumkin.
</div>
<?php endif; ?>

<!-- Return Form -->
<form method="POST" action="/new-pos/returns/process" id="returnForm">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <input type="hidden" name="sale_id" value="<?= $sale['id'] ?>">
    <input type="hidden" name="receipt" value="<?= $sale['chek_raqami'] ?>">
    
    <div class="table-card">
        <div class="table-header">
            <div class="table-title">
                <i class="fas fa-box"></i> Mahsulotlar
            </div>
            <div>
                <label class="me-3">
                    <input type="radio" name="return_type" value="partial" checked onchange="toggleReturnType()"> Qisman qaytarish
                </label>
                <label>
                    <input type="radio" name="return_type" value="full" onchange="toggleReturnType()"> To'liq qaytarish
                </label>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">Tanlash</th>
                        <th>Mahsulot</th>
                        <th>Shtrix kod</th>
                        <th>Narxi</th>
                        <th>Sotilgan</th>
                        <th>Qaytarish</th>
                        <th>Summa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalReturn = 0;
                    foreach ($items as $item): 
                        $maxReturn = $item['soni'];
                        $itemReturn = 0;
                    ?>
                    <tr class="product-row" data-id="<?= $item['id'] ?>">
                        <td class="text-center">
                            <input type="checkbox" 
                                   name="items[]" 
                                   value="<?= $item['id'] ?>" 
                                   class="return-checkbox"
                                   data-price="<?= $item['birlik_narx'] - $item['chegirma'] ?>"
                                   data-max="<?= $maxReturn ?>"
                                   onchange="updateRow(this)">
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($item['nomi']) ?></strong>
                        </td>
                        <td><?= $item['shtrix_kod'] ?></td>
                        <td><?= number_format($item['birlik_narx'] - $item['chegirma'], 0, ',', ' ') ?> so'm</td>
                        <td><?= $item['soni'] ?> <?= $item['birlik'] ?></td>
                        <td>
                            <input type="number" 
                                   name="quantities[<?= $item['id'] ?>]" 
                                   class="return-quantity" 
                                   min="0" 
                                   max="<?= $maxReturn ?>"
                                   value="0"
                                   disabled
                                   onchange="calculateTotal()">
                        </td>
                        <td class="item-total">0 so'm</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Return Summary -->
        <div class="total-return" id="totalReturn">
            Jami qaytarish: 0 so'm
        </div>
    </div>
    
    <!-- Return Reason -->
    <div class="table-card">
        <div class="table-header">
            <div class="table-title">
                <i class="fas fa-align-left"></i> Qaytarish sababi
            </div>
        </div>
        
        <div class="mb-3">
            <textarea name="reason" class="form-control" rows="3" placeholder="Qaytarish sababini kiriting" required></textarea>
        </div>
        
        <div class="d-flex justify-content-end gap-2">
            <a href="/new-pos/returns" class="btn btn-cancel">
                <i class="fas fa-times"></i> Bekor qilish
            </a>
            <button type="submit" class="btn btn-return" id="submitBtn">
                <i class="fas fa-undo-alt"></i> Qaytarishni amalga oshirish
            </button>
        </div>
    </div>
</form>