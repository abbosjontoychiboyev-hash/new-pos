<?php
// routes/web.php

return [
    'GET' => [
        // Auth routes
        '' => ['AuthController', 'loginForm'],
        'login' => ['AuthController', 'loginForm'],
        'logout' => ['AuthController', 'logout'],
        
        // Dashboard
        'dashboard' => ['DashboardController', 'index'],
        
        // Products
        'products' => ['ProductController', 'index'],
        'products/create' => ['ProductController', 'create'],
        'products/edit/{id}' => ['ProductController', 'edit'],
        
        // Categories
        'categories' => ['CategoryController', 'index'],
        'categories/create' => ['CategoryController', 'create'],
        'categories/edit/{id}' => ['CategoryController', 'edit'],
        
        // Subcategories
        'subcategories' => ['SubcategoryController', 'index'],
        'subcategories/create' => ['SubcategoryController', 'create'],
        'subcategories/edit/{id}' => ['SubcategoryController', 'edit'],
        
        
        // Customers
        'customers' => ['CustomerController', 'index'],
        'customers/create' => ['CustomerController', 'create'],
        'customers/edit/{id}' => ['CustomerController', 'edit'],
        'customers/debt/{id}' => ['CustomerController', 'debt'],
        // POS (Savdo)
        'pos' => ['PosController', 'index'],
        'pos/receipt/{id}' => ['PosController', 'receipt'],
        'pos/cart' => ['PosController', 'cart'],
        'pos/checkout' => ['PosController', 'checkout'],
        
        // Reports
        'reports' => ['ReportController', 'index'],
        'reports/sales' => ['ReportController', 'sales'],
        'reports/profit' => ['ReportController', 'profit'],
        'reports/debt' => ['ReportController', 'debt'],
        'reports/inventory' => ['ReportController', 'inventory'],
        
        // Settings
        'settings' => ['SettingController', 'index'],
        'settings/users' => ['UserController', 'index'],
        'settings/roles' => ['RoleController', 'index'],
        
        // API routes (AJAX)
        'api/products/search' => ['ApiController', 'searchProducts'],
        'api/customers/search' => ['ApiController', 'searchCustomers'],
        'api/categories' => ['ApiController', 'getCategories'],
        'api/subcategories/{id}' => ['ApiController', 'getSubcategories'],
        'api/get-subcategories' => ['ProductController', 'getSubcategories'],

        // Nasiya debt
        'debt' => ['DebtController', 'index'],
        'debt/customer/{id}' => ['DebtController', 'customer'],
        'debt/payment/{id}' => ['DebtController', 'payment'],
    ],
    
    'POST' => [
        // Auth
        'login' => ['AuthController', 'login'],
        
        // Products
        'products/store' => ['ProductController', 'store'],
        'products/update/{id}' => ['ProductController', 'update'],
        'products/delete/{id}' => ['ProductController', 'delete'],
        'products/adjust-stock' => ['ProductController', 'adjustStock'],
        
         // Categories
        'categories/store' => ['CategoryController', 'store'],
        'categories/update/{id}' => ['CategoryController', 'update'],
        'categories/delete/{id}' => ['CategoryController', 'delete'],
        
        // Subcategories
        'subcategories/store' => ['SubcategoryController', 'store'],
        'subcategories/update/{id}' => ['SubcategoryController', 'update'],
        'subcategories/delete/{id}' => ['SubcategoryController', 'delete'],
        
        // Customers
        'customers/store' => ['CustomerController', 'store'],
        'customers/update/{id}' => ['CustomerController', 'update'],
        'customers/delete/{id}' => ['CustomerController', 'delete'],
        
        // POS
        'pos/add-to-cart' => ['PosController', 'addToCart'],
        'pos/remove-from-cart' => ['PosController', 'removeFromCart'],
        'pos/clear-cart' => ['PosController', 'clearCart'],
        'pos/update-cart' => ['PosController', 'updateCart'],
        'pos/checkout' => ['PosController', 'checkout'],
        'pos/open-shift' => ['PosController', 'openShift'],
        'pos/close-shift' => ['PosController', 'closeShift'],
        
        
        // Settings
        'settings/update' => ['SettingController', 'update'],
        'settings/users/store' => ['UserController', 'store'],
        'settings/users/update/{id}' => ['UserController', 'update'],
        'settings/users/delete/{id}' => ['UserController', 'delete'],
        // nasiya debt
        'debt/payment/store' => ['DebtController', 'storePayment'],
        'debt/pay-full/{id}' => ['DebtController', 'payFull'],
    ],
    
    'AJAX' => [
        'api/products/search' => ['ApiController', 'searchProducts'],
        'api/customers/search' => ['ApiController', 'searchCustomers'],
        'api/check-stock' => ['ApiController', 'checkStock'],
        'api/get-product/{id}' => ['ApiController', 'getProduct'],
        'api/get-customer/{id}' => ['ApiController', 'getCustomer'],
        'pos/search-products' => ['PosController', 'searchProducts'],
        'pos/view-cart' => ['PosController', 'viewCart'],
        'pos/update-cart' => ['PosController', 'updateCart'],
    ]
];