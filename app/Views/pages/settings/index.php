<!-- Page Title -->
<?php $title = 'Sozlamalar'; ?>

<!-- Extra CSS -->
<?php ob_start(); ?>
<style>
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .settings-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
        border: 2px solid transparent;
    }
    
    .settings-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border-color: #667eea;
    }
    
    .settings-card .icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    
    .settings-card .icon i {
        font-size: 30px;
        color: white;
    }
    
    .settings-card .title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .settings-card .description {
        color: #666;
        font-size: 14px;
    }
    
    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $extraCss = ob_get_clean(); ?>

<!-- Page Content -->
<div class="settings-grid">
    <a href="/new-pos/settings/company" class="settings-card">
        <div class="icon">
            <i class="fas fa-building"></i>
        </div>
        <div class="title">Kompaniya ma'lumotlari</div>
        <div class="description">Kompaniya nomi, manzili, telefon raqami va boshqa ma'lumotlar</div>
    </a>
    
    <a href="/new-pos/settings/currency" class="settings-card">
        <div class="icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="title">Valyuta sozlamalari</div>
        <div class="description">Valyuta belgisi, format, o'nlik belgilar va boshqalar</div>
    </a>
    
    <a href="/new-pos/settings/pos" class="settings-card">
        <div class="icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="title">POS sozlamalari</div>
        <div class="description">Chek formati, avtomatik chop etish, to'lov usullari</div>
    </a>
    
    <a href="/new-pos/settings/users" class="settings-card">
        <div class="icon">
            <i class="fas fa-users-cog"></i>
        </div>
        <div class="title">Foydalanuvchilar</div>
        <div class="description">Foydalanuvchilarni boshqarish, rollar va ruxsatlar</div>
    </a>
    
    <a href="/new-pos/settings/profile" class="settings-card">
        <div class="icon">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="title">Mening profilim</div>
        <div class="description">Shaxsiy ma'lumotlar, parolni o'zgartirish</div>
    </a>
    
    <a href="/new-pos/settings/backup" class="settings-card">
        <div class="icon">
            <i class="fas fa-database"></i>
        </div>
        <div class="title">Zaxiralash</div>
        <div class="description">Ma'lumotlar bazasini zaxiralash va tiklash</div>
    </a>
</div>