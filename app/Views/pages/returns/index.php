<!-- Page Title -->
<?php $title = 'Mahsulot qaytarish'; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .return-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        max-width: 600px;
        margin: 0 auto;
    }
    
    .return-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .return-header i {
        font-size: 60px;
        color: #667eea;
        margin-bottom: 15px;
    }
    
    .return-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }
    
    .return-header p {
        color: #666;
    }
    
    .search-box {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .search-box input {
        flex: 1;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 16px;
    }
    
    .search-box input:focus {
        border-color: #667eea;
        outline: none;
    }
    
    .search-box button {
        padding: 12px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .search-box button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }
    
    .info-box {
        background: #e7f5ff;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }
    
    .info-box i {
        color: #667eea;
        margin-right: 10px;
    }
    
    .quick-links {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        justify-content: center;
    }
    
    .quick-links a {
        padding: 8px 15px;
        background: #f8f9fa;
        border-radius: 20px;
        color: #666;
        text-decoration: none;
        font-size: 13px;
        transition: all 0.3s;
    }
    
    .quick-links a:hover {
        background: #e0e0e0;
    }
    
    @media (max-width: 576px) {
        .return-card {
            padding: 20px;
        }
        
        .search-box {
            flex-direction: column;
        }
        
        .search-box button {
            width: 100%;
        }
        
        .quick-links {
            flex-direction: column;
        }
        
        .quick-links a {
            text-align: center;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Extra JavaScript -->
<?php ob_start(); ?>
<script>
    // Tooltips (ixtiyoriy)
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltips.length > 0) {
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        }
    });
</script>
<?php $extraJs = ob_get_clean(); ?>

<!-- Page Content -->
<div class="return-card">
    <div class="return-header">
        <i class="fas fa-undo-alt"></i>
        <h2>Mahsulot qaytarish</h2>
        <p>Chek raqami orqali savdoni qidiring</p>
    </div>
    
    <form action="/new-pos/returns/search" method="GET">
        <div class="search-box">
            <input type="text" 
                   name="receipt" 
                   placeholder="Chek raqamini kiriting (masalan: CHK-20240315-0001)"
                   required>
            <button type="submit">
                <i class="fas fa-search"></i> Qidirish
            </button>
        </div>
    </form>
    
    <div class="info-box">
        <i class="fas fa-info-circle"></i>
        <strong>Eslatma:</strong> Faqat yakunlangan savdolarni qaytarish mumkin. Qaytarilgan mahsulotlar omborga qaytariladi.
    </div>
    
    <div class="quick-links">
        <a href="/new-pos/returns/history"><i class="fas fa-history"></i> Qaytarish tarixi</a>
        <a href="/new-pos/pos"><i class="fas fa-shopping-cart"></i> Yangi savdo</a>
    </div>
</div>