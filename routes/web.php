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
        'reports/daily' => ['ReportController', 'daily'],
        'reports/monthly' => ['ReportController', 'monthly'],
        'reports/profit' => ['ReportController', 'profit'],
        'reports/top-products' => ['ReportController', 'topProducts'],
        'reports/cashiers' => ['ReportController', 'cashiers'],
        'reports/debtors' => ['ReportController', 'debtors'],
        'reports/categories' => ['ReportController', 'categories'],
        
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
        // Settings
        'settings' => ['SettingController', 'index'],
        'settings/company' => ['SettingController', 'company'],
        'settings/currency' => ['SettingController', 'currency'],
        'settings/pos' => ['SettingController', 'pos'],
        'settings/users' => ['SettingController', 'users'],
        'settings/users/create' => ['SettingController', 'userCreate'],
        'settings/users/edit/{id}' => ['SettingController', 'userEdit'],
        'settings/profile' => ['SettingController', 'profile'],

        // Returns
        'returns' => ['ReturnController', 'index'],
        'returns/search' => ['ReturnController', 'search'],
        'returns/history' => ['ReturnController', 'history'],

        //API 
        'api/sale-details/{id}' => ['ApiController', 'saleDetails'],

        //Dillers
        'yetkazib' => ['YetkazibBeruvchiController', 'index'],
        'yetkazib/create' => ['YetkazibBeruvchiController', 'create'],
        'yetkazib/edit/{id}' => ['YetkazibBeruvchiController', 'edit'],
        'yetkazib/add-payment/{id}' => ['YetkazibBeruvchiController', 'addPayment'],
        'yetkazib/show/{id}' => ['YetkazibBeruvchiController', 'show'],
        'yetkazib/add-payment/{id}' => ['YetkazibBeruvchiController', 'addPayment'],

        //Kirim 
        'kirim' => ['KirimController', 'index'],
        'kirim/create' => ['KirimController', 'create'],
        'kirim/show/{id}' => ['KirimController', 'show'],  // view -> show
        'kirim/edit/{id}' => ['KirimController', 'edit'],
            
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
        
        // Reports export
        'reports/export-excel/{type}' => ['ReportController', 'exportExcel'],
        'reports/export-pdf/{type}' => ['ReportController', 'exportPdf'],
        
        // Settings
        'settings/update' => ['SettingController', 'update'],
        'settings/users/store' => ['UserController', 'store'],
        'settings/users/update/{id}' => ['UserController', 'update'],
        'settings/users/delete/{id}' => ['UserController', 'delete'],
        // Settings
        'settings/company/save' => ['SettingController', 'saveCompany'],
        'settings/currency/save' => ['SettingController', 'saveCurrency'],
        'settings/pos/save' => ['SettingController', 'savePos'],
        'settings/users/store' => ['SettingController', 'userStore'],
        'settings/users/update/{id}' => ['SettingController', 'userUpdate'],
        'settings/users/delete/{id}' => ['SettingController', 'userDelete'],
        'settings/profile/update' => ['SettingController', 'profileUpdate'],
        
        // nasiya debt
        'debt/payment/store' => ['DebtController', 'storePayment'],
        'debt/pay-full/{id}' => ['DebtController', 'payFull'],
        

        //Returns
        'returns/process' => ['ReturnController', 'process'],

        //Dillers
        'yetkazib/store' => ['YetkazibBeruvchiController', 'store'],
        'yetkazib/update/{id}' => ['YetkazibBeruvchiController', 'update'],
        'yetkazib/delete/{id}' => ['YetkazibBeruvchiController', 'delete'],
        'yetkazib/store-payment' => ['YetkazibBeruvchiController', 'storePayment'],
        
         // Slot operatsiyalari
        'pos/create-slot'           => ['PosController', 'createSlot'],
        'pos/hold-slot'             => ['PosController', 'holdSlot'],
        'pos/activate-slot'         => ['PosController', 'activateSlot'],
        'pos/update-slot'           => ['PosController', 'updateSlot'],
        'pos/add-to-slot'           => ['PosController', 'addToSlot'],
        'pos/remove-from-slot'      => ['PosController', 'removeFromSlot'],
        'pos/update-slot-quantity'  => ['PosController', 'updateSlotQuantity'],
        'pos/checkout-slot'         => ['PosController', 'checkoutSlot'],
        'pos/add-to-cart'           => ['PosController', 'addToCart'],
        'pos/remove-from-cart'      => ['PosController', 'removeFromCart'], 
        'pos/clear-cart'            => ['PosController', 'clearCart'],
        'pos/update-cart'           => ['PosController', 'updateCart'],
       

        //Kirim
        'kirim/store' => ['KirimController', 'store'],
        'kirim/update/{id}' => ['KirimController', 'update'],
        'kirim/delete/{id}' => ['KirimController', 'delete'],

        
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