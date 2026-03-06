<!-- Page Title -->
<?php $title = 'Chek - ' . ($sale['chek_raqami'] ?? 'Savdo cheki'); ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    /* Ekranda ko'rinish uchun stillar */
    body {
        background: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 20px;
    }
    
    .receipt-container {
        max-width: 500px;
        margin: 0 auto;
    }
    
    .receipt-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .receipt-header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px dashed #e0e0e0;
    }
    
    .receipt-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }
    
    .receipt-header .chek-raqami {
        font-size: 18px;
        font-weight: 600;
        color: #667eea;
        margin-top: 10px;
        padding: 5px 10px;
        background: #f0f3ff;
        border-radius: 8px;
        display: inline-block;
    }
    
    .receipt-info {
        margin-bottom: 25px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .receipt-items {
        margin-bottom: 25px;
    }
    
    .receipt-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .item-name {
        flex: 2;
        font-weight: 500;
        color: #333;
    }
    
    .item-quantity {
        flex: 1;
        text-align: center;
        color: #666;
    }
    
    .item-price {
        flex: 1;
        text-align: right;
        font-weight: 600;
        color: #667eea;
    }
    
    .receipt-summary {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px solid #e0e0e0;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 16px;
    }
    
    .summary-row.total {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #e0e0e0;
    }
    
    .payment-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-tolangan {
        background: #d4edda;
        color: #155724;
    }
    
    .status-qisman {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-nasiya {
        background: #f8d7da;
        color: #721c24;
    }
    
    .receipt-footer {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 2px dashed #e0e0e0;
        color: #999;
        font-size: 13px;
    }
    
    .receipt-actions {
        margin-top: 20px;
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    
    .btn-print {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-back {
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        padding: 12px 30px;
        border-radius: 10px;
        color: #666;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-block;
    }
    
    /* PRINT STILLARI - FAQAT CHEK UCHUN */
    @media print {
        /* Sahifadagi hamma narsani yashirish */
        body * {
            visibility: hidden;
        }
        
        /* Faqat chekni ko'rsatish */
        #print-section, #print-section * {
            visibility: visible;
        }
        
        /* Chekni to'liq sahifada markazlash */
        #print-section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 80mm;  /* Termal printer uchun */
            margin: 0 auto;
            padding: 5mm;
            background: white;
            font-family: 'Courier New', monospace;
        }
        
        /* Printer uchun stillar */
        #print-section .receipt-card {
            box-shadow: none;
            padding: 0;
            background: white;
        }
        
        #print-section .receipt-header {
            border-bottom: 1px dashed #000;
        }
        
        #print-section .receipt-header h2 {
            color: #000;
            font-size: 16px;
        }
        
        #print-section .chek-raqami {
            background: none;
            color: #000;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            border-radius: 0;
        }
        
        #print-section .receipt-info {
            background: none;
            padding: 0;
        }
        
        #print-section .info-label,
        #print-section .info-value {
            color: #000;
        }
        
        #print-section .item-price {
            color: #000;
        }
        
        #print-section .payment-status {
            background: none;
            color: #000;
            border: 1px solid #000;
        }
        
        #print-section .receipt-footer {
            color: #000;
        }
        
        #print-section .btn-print,
        #print-section .btn-back {
            display: none;
        }
    }
    
    /* Mobile uchun */
    @media (max-width: 576px) {
        .receipt-actions {
            flex-direction: column;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Faqat chekni print qilish funksiyasi
    function printReceipt() {
        // Print oynasini ochish
        window.print();
    }
    
    // Avtomatik print (agar kerak bo'lsa)
    <?php if (isset($_SESSION['auto_print']) && $_SESSION['auto_print']): ?>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            window.print();
        }, 500);
    });
    <?php unset($_SESSION['auto_print']); ?>
    <?php endif; ?>
    
    // Print tugmasini bosganda
    document.querySelector('.btn-print')?.addEventListener('click', function(e) {
        e.preventDefault();
        printReceipt();
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<div class="receipt-container">
    <!-- PRINT SECTION - FAQAT SHU QISM PRINT QILINADI -->
    <div id="print-section">
        <div class="receipt-card">
            <!-- Receipt Header -->
            <div class="receipt-header">
                <h2>POS MAGAZIN</h2>
                <p>Savdo cheki</p>
                <div class="chek-raqami">
                    № <?php 
                    if (isset($sale['chek_raqami']) && !empty($sale['chek_raqami'])) {
                        echo htmlspecialchars($sale['chek_raqami']);
                    } elseif (isset($sale['chek_raqam']) && !empty($sale['chek_raqam'])) {
                        echo htmlspecialchars($sale['chek_raqam']);
                    } elseif (isset($sale['id'])) {
                        echo 'CHK-' . date('Ymd', strtotime($sale['sotilgan_vaqt'])) . '-' . str_pad($sale['id'], 4, '0', STR_PAD_LEFT);
                    } else {
                        echo 'CHK-' . date('Ymd') . '-0001';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Receipt Info -->
            <div class="receipt-info">
                <div class="info-row">
                    <span class="info-label">Sana:</span>
                    <span class="info-value"><?= date('d.m.Y H:i', strtotime($sale['sotilgan_vaqt'] ?? 'now')) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kassir:</span>
                    <span class="info-value"><?= htmlspecialchars($sale['kassir_fio'] ?? 'Kassir') ?></span>
                </div>
                <?php if (isset($sale['mijoz_fio']) && !empty($sale['mijoz_fio'])): ?>
                <div class="info-row">
                    <span class="info-label">Mijoz:</span>
                    <span class="info-value"><?= htmlspecialchars($sale['mijoz_fio']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (isset($sale['mijoz_tel']) && !empty($sale['mijoz_tel'])): ?>
                <div class="info-row">
                    <span class="info-label">Tel:</span>
                    <span class="info-value"><?= htmlspecialchars($sale['mijoz_tel']) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Receipt Items -->
            <div class="receipt-items">
                <div class="receipt-item" style="font-weight: 600;">
                    <div class="item-name">Mahsulot</div>
                    <div class="item-quantity">Soni</div>
                    <div class="item-price">Summa</div>
                </div>
                
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                    <div class="receipt-item">
                        <div class="item-name">
                            <?= htmlspecialchars($item['nomi'] ?? 'Mahsulot') ?>
                            <?php if (isset($item['shtrix_kod']) && !empty($item['shtrix_kod'])): ?>
                                <br><small style="font-size: 9px;"><?= htmlspecialchars($item['shtrix_kod']) ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="item-quantity">
                            <?= $item['soni'] ?? 1 ?>
                        </div>
                        <div class="item-price">
                            <?= number_format($item['qator_summa'] ?? 0, 0, ',', ' ') ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-3">Mahsulotlar topilmadi</div>
                <?php endif; ?>
            </div>
            
            <!-- To'lov ma'lumotlari -->
            <div class="receipt-info" style="margin-top: 10px;">
                <div class="info-row">
                    <span class="info-label">To'lov usuli:</span>
                    <span class="info-value">
                        <?php 
                        $usul = $sale['tolov_usuli'] ?? 'NAQD';
                        switch($usul) {
                            case 'NAQD': echo 'NAQD'; break;
                            case 'KARTA': echo 'KARTA'; break;
                            case 'OTKAZMA': echo 'OTKAZMA'; break;
                            case 'ARALASH': echo 'ARALASH'; break;
                            default: echo $usul;
                        }
                        ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Holati:</span>
                    <span class="info-value">
                        <span class="payment-status status-<?= strtolower($sale['tolov_holati'] ?? 'TOLANGAN') ?>">
                            <?php 
                            $holat = $sale['tolov_holati'] ?? 'TOLANGAN';
                            switch($holat) {
                                case 'TOLANGAN': echo 'TOLANGAN'; break;
                                case 'QISMAN': echo 'QISMAN'; break;
                                case 'NASIYA': echo 'NASIYA'; break;
                                default: echo $holat;
                            }
                            ?>
                        </span>
                    </span>
                </div>
            </div>
            
            <!-- Receipt Summary -->
            <div class="receipt-summary">
                <div class="summary-row">
                    <span>Jami:</span>
                    <span><?= number_format($sale['umumiy_summa'] ?? 0, 0, ',', ' ') ?></span>
                </div>
                
                <?php if (($sale['chegirma_summa'] ?? 0) > 0): ?>
                <div class="summary-row">
                    <span>Chegirma:</span>
                    <span>-<?= number_format($sale['chegirma_summa'] ?? 0, 0, ',', ' ') ?></span>
                </div>
                <?php endif; ?>
                
                <div class="summary-row total">
                    <span>YAKUNIY:</span>
                    <span><?= number_format($sale['yakuniy_summa'] ?? 0, 0, ',', ' ') ?></span>
                </div>
                
                <?php if (($sale['tolangan_summa'] ?? 0) > 0): ?>
                <div class="summary-row">
                    <span>To'landi:</span>
                    <span><?= number_format($sale['tolangan_summa'] ?? 0, 0, ',', ' ') ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (($sale['qarz_summa'] ?? 0) > 0): ?>
                <div class="summary-row" style="font-weight: 600;">
                    <span>Qarz:</span>
                    <span><?= number_format($sale['qarz_summa'] ?? 0, 0, ',', ' ') ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Receipt Footer -->
            <div class="receipt-footer">
                <p>Savdo uchun rahmat!</p>
                <p>Tel: +998 (20) 000 79 89</p>
                <p>Farg'ona sh., Eco City 68</p>
                <p><?= date('d.m.Y H:i:s') ?></p>
                
            </div>
        </div>
    </div>
    
    <!-- Receipt Actions (faqat ekranda) -->
    <div class="receipt-actions">
        <button class="btn-print" onclick="printReceipt()">
            <i class="fas fa-print"></i> Chekni chop etish
        </button>
        <a href="/new-pos/pos" class="btn-back">
            <i class="fas fa-arrow-left"></i> POS ga qaytish
        </a>
    </div>
</div>