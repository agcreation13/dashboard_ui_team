<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Seller/Shop Details
    |--------------------------------------------------------------------------
    |
    | These details will be used in all invoices (PDF, Print, Show, Create, Edit)
    | Update these values to change seller information across all invoices.
    |
    */

    'name' => env('SELLER_NAME', 'SIDDHI AYURVEDIC'),
    'address' => env('SELLER_ADDRESS', 'MIDAS HEIGHTS, SECTOR-7, HDIL LAYOUT, VIRAR(W), PALGHAR-401303'),
    'email' => env('SELLER_EMAIL', 'siddhiayurvedic009@gmail.com'),
    'phone' => env('SELLER_PHONE', '9021350010'),
    'gstin' => env('SELLER_GSTIN', '27BXFPP6045K1Z1'),
];

