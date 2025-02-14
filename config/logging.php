<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment Logging Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines custom log channels specifically for handling
    | payment-related logs. Each log channel can be used for different
    | levels of logging, such as errors, info logs, or success messages.
    |
    | Log files are stored in the `storage/logs` directory to keep track
    | of payment transactions, failures, and successes.
    |
    */

    'channels' => [

        /*
        |--------------------------------------------------------------------------
        | Payment Errors Log Channel
        |--------------------------------------------------------------------------
        |
        | This channel is dedicated to logging errors that occur during
        | payment processing. Any failed transactions or unexpected
        | behavior in the payment flow should be recorded here.
        |
        | Log Level: ERROR
        | Log File: storage/logs/payment-errors.log
        |
        */

        'payment_errors' => [
            'driver' => 'single',
            'path' => storage_path('logs/payment-errors.log'),
            'level' => 'error',
        ],

        /*
        |--------------------------------------------------------------------------
        | Payment Information Log Channel
        |--------------------------------------------------------------------------
        |
        | This channel logs general payment information, such as when
        | a payment attempt is initiated, pending transactions, or any
        | non-critical payment events.
        |
        | Log Level: INFO
        | Log File: storage/logs/payment-info.log
        |
        */

        'payment_info' => [
            'driver' => 'single',
            'path' => storage_path('logs/payment-info.log'),
            'level' => 'info',
        ],

        /*
        |--------------------------------------------------------------------------
        | Payment Success Log Channel
        |--------------------------------------------------------------------------
        |
        | This channel is used to log successfully processed payments.
        | It records completed transactions to ensure tracking of
        | payments that have gone through without issues.
        |
        | Log Level: INFO
        | Log File: storage/logs/payment-success.log
        |
        */

        'payment_success' => [
            'driver' => 'single',
            'path' => storage_path('logs/payment-success.log'),
            'level' => 'info',
        ],

    ],

];
