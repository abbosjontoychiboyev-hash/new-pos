<?php
declare(strict_types=1);

// API routes
return [
    'GET' => [
        'api/sale-details/{id}' => ['ApiController', 'saleDetails'],
        'api/products/{id}' => ['ApiController', 'getProduct'],
        'api/customers/{id}' => ['ApiController', 'getCustomer'],
        'api/categories' => ['ApiController', 'getCategories'],
        'api/subcategories/{id}' => ['ApiController', 'getSubcategories'],
        'api/check-stock' => ['ApiController', 'checkStock'],
    ],
    'POST' => [
        // AJAX POST routes if needed
    ],
    'AJAX' => [
        'api/products/search' => ['ApiController', 'searchProducts'],
        'api/customers/search' => ['ApiController', 'searchCustomers'],
    ]
];
