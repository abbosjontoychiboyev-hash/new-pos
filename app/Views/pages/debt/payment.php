<!-- Page Title -->
<?php $title = 'To\'lov qabul qilish - ' . htmlspecialchars($sale['chek_raqami'] ?? ''); ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .payment-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        max-width: 600px;
        margin: 0 auto;
    }
    
    .payment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .payment-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }
    
    .payment-title i {
        color: #667eea;
        margin-right: 10px;
    }
    
    .sale-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 25px;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .info-label {
        color: #666;
        font-weight: 500;
    }
    
    .info-value {
        font-weight: 600;
        color: #333;
    }
    
    .remaining-debt {
        background: #fff3cd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-left: 4px solid #ffc107;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .remaining-debt .label {
        font-weight: 600;
        color: #856404;
    }
    
    .remaining-debt .amount {
        font-size: 24px;
        font-weight: 700;
        color: #856404;
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
        width: 100%;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        outline: none;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
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
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-cancel:hover {
        background: #e0e0e0;
    }
    
    .payment-methods {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .payment-method {
        flex: 1;
        min-width: 100px;
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
        font-size: 24px;
        margin-bottom: 5px;
        color: #667eea;
    }
    
    @media (max-width: 576px) {
        .payment-card {
            padding: 20px;
        }
        
        .payment-methods {
            flex-direction: column;
        }
        
        .remaining-debt {
            flex-direction: column;
            text-align: center;
        }
        
        .payment-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Tanlangan to'lov usulini saqlash
    let selectedMethod = 'NAQD';
    
    // To'lov usulini tanlash funksiyasi
    function selectPaymentMethod(method) {
        selectedMethod = method;
        // Barcha usullardan active klassni olib tashlash
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('active');
        });
        // Tanlangan usulga active klass qo'shish
        document.querySelector(`.payment-method[data-method="${method}"]`).classList.add('active');
        // Yashirin inputga qiymatni yozish
        document.getElementById('usul').value = method;
    }
    
    // Sahifa yuklanganda
    document.addEventListener('DOMContentLoaded', function() {
        // Default usulni faollashtirish (NAQD)
        selectPaymentMethod('NAQD');
        
        // To'lov summasini formatlash (faqat raqamlar)
        const summaInput = document.getElementById('summa');
        if (summaInput) {
            summaInput.addEventListener('keyup', function() {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            });
        }
        
        // Formani yuborishdan oldin validatsiya
        document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
            const summa = parseFloat(document.getElementById('summa').value) || 0;
            const qolganQarz = <?= $sale['qarz_summa'] ?? 0 ?>;
            
            if (summa <= 0) {
                e.preventDefault();
                alert('Iltimos, to\'lov summasini kiriting');
                return;
            }
            
            if (summa > qolganQarz) {
                if (!confirm('To\'lov summasi qarzdan oshib ketadi. Ortib qolgan summa mijozga qaytariladi. Davom etasizmi?')) {
                    e.preventDefault();
                }
            }
        });
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<div class="payment-card">
    <div class="payment-header">
        <div class="payment-title">
            <i class="fas fa-money-bill-wave"></i> To'lov qabul qilish
        </div>
        <a href="/new-pos/debt/customer/<?= $sale['mijoz_id'] ?>" class="btn btn-cancel">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>
    
    <!-- Savdo ma'lumotlari -->
    <div class="sale-info">
        <div class="info-row">
            <span class="info-label">Chek raqami:</span>
            <span class="info-value"><?= htmlspecialchars($sale['chek_raqami'] ?? '') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Mijoz:</span>
            <span class="info-value"><?= htmlspecialchars($sale['mijoz_fio'] ?? 'Anonim') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Savdo sanasi:</span>
            <span class="info-value"><?= date('d.m.Y H:i', strtotime($sale['sotilgan_vaqt'] ?? 'now')) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Savdo summasi:</span>
            <span class="info-value"><?= number_format($sale['yakuniy_summa'] ?? 0, 0, ',', ' ') ?> so'm</span>
        </div>
        <div class="info-row">
            <span class="info-label">Oldin to'langan:</span>
            <span class="info-value"><?= number_format($sale['tolangan_summa'] ?? 0, 0, ',', ' ') ?> so'm</span>
        </div>
    </div>
    
    <!-- Qolgan qarz -->
    <div class="remaining-debt">
        <span class="label">Qolgan qarz:</span>
        <span class="amount"><?= number_format($sale['qarz_summa'] ?? 0, 0, ',', ' ') ?> so'm</span>
    </div>
    
    <!-- Flash xabarlar (layoutda bor, lekin qo'shimcha ko'rsatish) -->
    <?php if (isset($_SESSION['flash']['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['flash']['error'] ?></div>
        <?php unset($_SESSION['flash']['error']); ?>
    <?php endif; ?>
    
    <!-- To'lov formasi -->
    <form method="POST" action="/new-pos/debt/payment/store" id="paymentForm">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="savdo_id" value="<?= $sale['id'] ?>">
        <input type="hidden" name="mijoz_id" value="<?= $sale['mijoz_id'] ?>">
        <input type="hidden" name="usul" id="usul" value="NAQD">
        
        <!-- To'lov usullari -->
        <label class="form-label">To'lov usuli</label>
        <div class="payment-methods">
            <div class="payment-method active" data-method="NAQD" onclick="selectPaymentMethod('NAQD')">
                <i class="fas fa-money-bill-wave"></i>
                <span>Naqd</span>
            </div>
            <div class="payment-method" data-method="KARTA" onclick="selectPaymentMethod('KARTA')">
                <i class="fas fa-credit-card"></i>
                <span>Karta</span>
            </div>
            <div class="payment-method" data-method="OTKAZMA" onclick="selectPaymentMethod('OTKAZMA')">
                <i class="fas fa-exchange-alt"></i>
                <span>O'tkazma</span>
            </div>
        </div>
        
        <!-- To'lov summasi -->
        <div class="mb-3">
            <label for="summa" class="form-label">To'lov summasi <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control" 
                   id="summa" 
                   name="summa" 
                   value="<?= $sale['qarz_summa'] ?? 0 ?>"
                   required>
            <small class="text-muted">Qolgan qarz: <?= number_format($sale['qarz_summa'] ?? 0, 0, ',', ' ') ?> so'm</small>
        </div>
        
        <!-- Izoh -->
        <div class="mb-3">
            <label for="izoh" class="form-label">Izoh (ixtiyoriy)</label>
            <textarea class="form-control" id="izoh" name="izoh" rows="2" placeholder="To'lov haqida qo'shimcha ma'lumot..."></textarea>
        </div>
        
        <hr class="my-4">
        
        <!-- Tugmalar -->
        <div class="d-flex justify-content-end gap-2">
            <a href="/new-pos/debt/customer/<?= $sale['mijoz_id'] ?>" class="btn btn-cancel">
                <i class="fas fa-times"></i> Bekor qilish
            </a>
            <button type="submit" class="btn btn-save">
                <i class="fas fa-save"></i> To'lovni qabul qilish
            </button>
        </div>
    </form>
</div>