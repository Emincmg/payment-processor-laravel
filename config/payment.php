<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may define all the credentials required to integrate with the
    | Stripe API. These values are pulled from your environment file to keep
    | sensitive information secure.
    |
    */

    'stripe' => [

        /*
        |--------------------------------------------------------------------------
        | Stripe Secret Key
        |--------------------------------------------------------------------------
        |
        | This is the API secret key for authenticating requests to Stripe.
        | You can find your API keys in the Stripe dashboard.
        | Never expose this key in frontend code or public repositories.
        |
        | Environment Variable: STRIPE_SECRET
        |
        */

        'secret' => env('STRIPE_SECRET'),

        /*
        |--------------------------------------------------------------------------
        | Stripe Return URL
        |--------------------------------------------------------------------------
        |
        | The return URL is used to redirect the user after completing the
        | payment process. Stripe will send users back to this URL once
        | the payment is processed successfully or fails.
        |
        | Environment Variable: STRIPE_RETURN_URL
        |
        */

        'return_url' => env('STRIPE_RETURN_URL', 'http://your-app/return-url'),
    ],

    /*
   |--------------------------------------------------------------------------
   | PayPal API Credentials
   |--------------------------------------------------------------------------
   |
   | The credentials used to authenticate with PayPal's REST API.
   | You can retrieve these from your PayPal Developer Dashboard.
   |
   | Environment Variables:
   | PAYPAL_CLIENT_ID, PAYPAL_SECRET
   |
   */

    'client_id' => env('PAYPAL_CLIENT_ID'),

    'secret' => env('PAYPAL_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | PayPal Mode (Sandbox or Live)
    |--------------------------------------------------------------------------
    |
    | The mode in which PayPal API will operate:
    | - "sandbox" for testing transactions.
    | - "live" for real payments.
    |
    | Environment Variable: PAYPAL_MODE
    |
    */

    'mode' => env('PAYPAL_MODE', 'sandbox'), // Default to sandbox mode.

    /*
    |--------------------------------------------------------------------------
    | PayPal Redirect URLs
    |--------------------------------------------------------------------------
    |
    | These URLs are used to redirect users after they complete or cancel
    | the payment process. Ensure these match your application routes.
    |
    | Environment Variables:
    | PAYPAL_RETURN_URL, PAYPAL_CANCEL_URL
    |
    */

    'return_url' => env('PAYPAL_RETURN_URL', 'https://your-app.com/payment/success'),

    'cancel_url' => env('PAYPAL_CANCEL_URL', 'https://your-app.com/payment/cancel'),

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Define whether to enable PayPal API logging and specify log level.
    | Options: 'DEBUG', 'INFO', 'WARN', 'ERROR'
    |
    | Environment Variable: PAYPAL_LOG_LEVEL
    |
    */

    'log_enabled' => env('PAYPAL_LOG_ENABLED', true),

    'log_level' => env('PAYPAL_LOG_LEVEL', 'ERROR'),
];
