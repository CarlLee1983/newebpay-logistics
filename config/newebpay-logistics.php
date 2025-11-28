<?php

return [
    /*
    |--------------------------------------------------------------------------
    | NewebPay Logistics Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your NewebPay Logistics settings.
    |
    */

    'merchant_id' => env('NEWEBPAY_LOGISTICS_MERCHANT_ID', ''),
    'hash_key' => env('NEWEBPAY_LOGISTICS_HASH_KEY', ''),
    'hash_iv' => env('NEWEBPAY_LOGISTICS_HASH_IV', ''),

    /*
    |--------------------------------------------------------------------------
    | Server URL
    |--------------------------------------------------------------------------
    |
    | The URL of the NewebPay Logistics API.
    | Default is the testing environment.
    | Production: https://core.newebpay.com/API/Logistic
    | Testing: https://ccore.newebpay.com/API/Logistic
    |
    */
    'server_url' => env('NEWEBPAY_LOGISTICS_SERVER_URL', 'https://ccore.newebpay.com/API/Logistic'),
];
