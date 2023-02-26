<?php

// SSLCommerz configuration

return [
    //'projectPath' => env('PROJECT_PATH'),
   // 'projectPath' => 'https://sandbox.sslcommerz.com',
    // For Sandbox, use "https://sandbox.sslcommerz.com"
    // For Live, use "https://securepay.sslcommerz.com"
    'apiDomain' => env("API_DOMAIN_URL", "https://sandbox.sslcommerz.com"),
    'apiCredentials' => [
        'store_id' => env("SSLCZ_STORE_ID"),
        'store_password' => env("SSLCZ_STORE_PASSWD"),
    ],
    'apiUrl' => [
        'make_payment' => "/gwprocess/v4/api.php",
        'transaction_status' => "/validator/api/merchantTransIDvalidationAPI.php",
        'order_validate' => "/validator/api/validationserverAPI.php",
        'refund_payment' => "/validator/api/merchantTransIDvalidationAPI.php",
        'refund_status' => "/validator/api/merchantTransIDvalidationAPI.php",
    ],
    'connect_from_localhost' => env("IS_LOCALHOST", true), // For Sandbox, use "true", For Live, use "false"
    'success_url' => '/student/success',
    'failed_url' => '/student/fail',
    'cancel_url' => '/student/cancel',
    'ipn_url' => '/student/ipn',
];
