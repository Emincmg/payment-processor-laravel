<?php

namespace  Emincmg\PaymentProcessorLaravel\Factory;

use  Emincmg\PaymentProcessorLaravel\Classes\StripePayment;
use  Emincmg\PaymentProcessorLaravel\Classes\PaypalPayment;

class PaymentFactory
{
    /**
     * Create a payment gateway instance dynamically.
     *
     * @param string $gateway The payment gateway identifier (e.g., 'stripe', 'paypal' etc.).
     * @param array $data The required parameters for the payment instance.
     * @return StripePayment|PaypalPayment
     * @throws Exception
     */
    public static function create(string $gateway, array $data): StripePayment|PaypalPayment
    {
        return match ($gateway) {
            'stripe' => new StripePayment(...$data),
            'paypal' => new PaypalPayment(...$data),
            'vpos' => new VakifbankVPosPayment(...$data),
            default => throw new Exception("Invalid payment gateway: $gateway"),
        };
    }
}