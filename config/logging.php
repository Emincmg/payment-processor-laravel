<?php
return [
    'channels' => [
        'payment_errors' => [
            'driver' => 'single',
            'path' => storage_path('logs/payment-errors.log'),
            'level' => 'error',
        ],
        'payment_info' => [
            'driver' => 'single',
            'path' => storage_path('logs/payment-info.log'),
            'level' => 'info',
        ],
        'payment_success' => [
            'driver' => 'single',
            'path' => storage_path('logs/payment-success.log'),
            'level' => 'info',
        ],
    ],
];