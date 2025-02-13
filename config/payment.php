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

        'return_url' => env('STRIPE_RETURN_URL'),
    ],
];
