<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chek - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        
        .receipt-header p {
            color: #666;
            margin: 0;
            font-size: 14px;
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
        
        .info-label {
            color: #666;
        }
        
        .info-value {
            font-weight: 600;
            color: #333;
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
        
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.3);
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
        
        .btn-back:hover {
            background: #e0e0e0;
        }
        
        @media print {
            body { background: white; }
            .receipt-actions { display: none; }
            .btn-back { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-card">
            <!-- Receipt Header -->
            <div class="receipt-header">
                <h2>POS Magazin</h2>
                <p>Savdo cheki</p>
                <div class="chek-raqami">
                    № <?php 
                    // Chek raqamini turli variantlarda olish
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
                    <span class="info-label">Telefon:</span>
                    <span class="info-value"><?= htmlspecialchars($sale['mijoz_tel']) ?></span>
                </div>
                <?php endif; ?>
                <div class="info-row">
                    <span class="info-label">To'lov usuli:</span>
                    <span class="info-value">
                        <?php 
                        $usul = $sale['tolov_usuli'] ?? 'NAQD';
                        switch($usul) {
                            case 'NAQD': echo 'Naqd pul'; break;
                            case 'KARTA': echo 'Plastik karta'; break;
                            case 'OTKAZMA': echo 'Pul o\'tkazma'; break;
                            case 'ARALASH': echo 'Aralash'; break;
                            default: echo $usul;
                        }
                        ?>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">To'lov holati:</span>
                    <span class="info-value">
                        <?php 
                        $holat = $sale['tolov_holati'] ?? 'TOLANGAN';
                        $statusClass = '';
                        $statusText = '';
                        
                        switch($holat) {
                            case 'TOLANGAN':
                                $statusClass = 'status-tolangan';
                                $statusText = 'To\'langan';
                                break;
                            case 'QISMAN':
                                $statusClass = 'status-qisman';
                                $statusText = 'Qisman to\'langan';
                                break;
                            case 'NASIYA':
                                $statusClass = 'status-nasiya';
                                $statusText = 'Nasiya';
                                break;
                            default:
                                $statusClass = 'status-tolangan';
                                $statusText = $holat;
                        }
                        ?>
                        <span class="payment-status <?= $statusClass ?>">
                            <?= $statusText ?>
                        </span>
                    </span>
                </div>
            </div>
            
            <!-- Receipt Items -->
            <div class="receipt-items">
                <div class="receipt-item" style="font-weight: 600; color: #333;">
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
                                <small style="display: block; font-size: 11px; color: #999;"><?= htmlspecialchars($item['shtrix_kod']) ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="item-quantity">
                            <?= $item['soni'] ?? 1 ?>
                        </div>
                        <div class="item-price">
                            <?= number_format($item['qator_summa'] ?? 0, 0, ',', ' ') ?> so'm
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-3">Mahsulotlar topilmadi</div>
                <?php endif; ?>
            </div>
            
            <!-- Receipt Summary -->
            <div class="receipt-summary">
                <div class="summary-row">
                    <span>Jami:</span>
                    <span><?= number_format($sale['umumiy_summa'] ?? 0, 0, ',', ' ') ?> so'm</span>
                </div>
                
                <?php if (($sale['chegirma_summa'] ?? 0) > 0): ?>
                <div class="summary-row">
                    <span>Chegirma:</span>
                    <span>-<?= number_format($sale['chegirma_summa'] ?? 0, 0, ',', ' ') ?> so'm</span>
                </div>
                <?php endif; ?>
                
                <div class="summary-row total">
                    <span>Yakuniy:</span>
                    <span><?= number_format($sale['yakuniy_summa'] ?? 0, 0, ',', ' ') ?> so'm</span>
                </div>
                
                <?php if (($sale['tolangan_summa'] ?? 0) > 0): ?>
                <div class="summary-row">
                    <span>To'langan:</span>
                    <span><?= number_format($sale['tolangan_summa'] ?? 0, 0, ',', ' ') ?> so'm</span>
                </div>
                <?php endif; ?>
                
                <?php if (($sale['qarz_summa'] ?? 0) > 0): ?>
                <div class="summary-row" style="color: #dc3545;">
                    <span>Qarz:</span>
                    <span><?= number_format($sale['qarz_summa'] ?? 0, 0, ',', ' ') ?> so'm</span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Receipt Footer -->
            <div class="receipt-footer">
                <p>Savdo uchun rahmat!</p>
                <p>Tel: +998 (20) 000 79 89</p>
                <p>Farg'ona sh., Eco City 68-uy</p>
                <p class="mt-2"><?= date('d.m.Y H:i:s') ?></p>
            </div>
            
            <!-- Receipt Actions -->
            <div class="receipt-actions">
                <button class="btn-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Chekni chop etish
                </button>
                <a href="/new-pos/pos" class="btn-back">
                    <i class="fas fa-arrow-left"></i> POS ga qaytish
                </a>
            </div>
        </div>
    </div>
    
    <script>
        // Auto print dialog (ixtiyoriy)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 500);
        // }
    </script>
</body>
</html>