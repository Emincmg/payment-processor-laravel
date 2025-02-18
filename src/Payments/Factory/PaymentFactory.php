<?php

namespace  Emincmg\PaymentProcessorLaravel\Payments\Factory;

use Emincmg\PaymentProcessorLaravel\Payments\Strategy\PaypalPayment;
use Emincmg\PaymentProcessorLaravel\Payments\Strategy\StripePayment;
use Emincmg\PaymentProcessorLaravel\Payment;

class PaymentFactory
{
    /**
     * Create a payment gateway instance dynamically.
     *
     * @param string $gateway The payment gateway identifier (e.g., 'stripe', 'paypal' etc.).
     * @param array $data The required parameters for the payment instance.
     * @return Payment The created payment instance.
     * @throws \Exception If an invalid payment gateway is provided.
     */
    public static function create(string $gateway, array $data): Payment
    {
        return match ($gateway) {
            'stripe' => new StripePayment(...$data),
            'paypal' => new PaypalPayment(...$data),
            default => throw new \Exception("Invalid payment gateway: $gateway"),
        };
    }
}