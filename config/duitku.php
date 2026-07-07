<?php

return [
    'merchant_code' => env('DUITKU_MERCHANT_CODE', ''),
    'api_key' => env('DUITKU_API_KEY', ''),
    'sandbox' => env('DUITKU_SANDBOX', true),
    'callback_url' => env('DUITKU_CALLBACK_URL'),
    'return_url' => env('DUITKU_RETURN_URL'),
    'default_payment_method' => env('DUITKU_DEFAULT_PAYMENT_METHOD', 'SP'),
    'expiry_period' => (int) env('DUITKU_EXPIRY_PERIOD', 1440),
];
