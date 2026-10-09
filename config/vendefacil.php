<?php

return [
    'free_product_limit' => (int) env('VENDEFACIL_FREE_PRODUCT_LIMIT', 10),
    'unlimited_products_price' => env('VENDEFACIL_UNLIMITED_PRODUCTS_PRICE', '15.00'),
    'payment_number' => env('VENDEFACIL_PAYMENT_NUMBER', '989652889'),
    'payment_whatsapp' => env('VENDEFACIL_PAYMENT_WHATSAPP', '51989652889'),
    'featured_days' => (int) env('VENDEFACIL_FEATURED_DAYS', 30),
];
