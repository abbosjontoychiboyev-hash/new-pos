// public/assets/js/app.js

// Flash xabarlarni avtomatik yopish
document.addEventListener('DOMContentLoaded', function() {
    // Alertlarni 5 soniyadan keyin yopish
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);
});

// Pul formatlash
function formatMoney(amount) {
    return new Intl.NumberFormat('uz-UZ', { 
        style: 'currency', 
        currency: 'UZS',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount).replace('UZS', '').trim() + ' so\'m';
}

// Sana formatlash
function formatDate(dateString, format = 'dd.mm.yyyy hh:ii') {
    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    
    return format
        .replace('dd', day)
        .replace('mm', month)
        .replace('yyyy', year)
        .replace('hh', hours)
        .replace('ii', minutes);
}

// Mahsulot qidirish (AJAX)
function searchProducts(keyword, callback) {
    fetch(baseUrl + '/api/products/search?q=' + encodeURIComponent(keyword))
        .then(response => response.json())
        .then(data => callback(data))
        .catch(error => console.error('Xatolik:', error));
}

// Mahsulotni o'chirish
function deleteProduct(id) {
    if (confirm('Haqiqatan ham bu mahsulotni o\'chirmoqchimisiz?')) {
        const form = document.getElementById('delete-form');
        form.action = baseUrl + '/products/delete/' + id;
        form.submit();
    }
}

// Miqdor modalini ko'rsatish
function showStockModal(id, name, currentStock) {
    document.getElementById('stock_product_id').value = id;
    document.getElementById('stock_product_name').value = name;
    document.getElementById('stock_current').value = currentStock;
    
    const modal = new bootstrap.Modal(document.getElementById('stockModal'));
    modal.show();
}

// Base URL ni JavaScript ga o'tkazish
const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';