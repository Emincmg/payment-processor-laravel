<?php

namespace  Emincmg\PaymentProcessorLaravel\Payments\Factory;

use Emincmg\PaymentProcessorLaravel\Classes\PaypalPayment;
use Emincmg\PaymentProcessorLaravel\Classes\StripePayment;
use Emincmg\PaymentProcessorLaravel\Factory\Exception;
use Emincmg\PaymentProcessorLaravel\Factory\Payment;

class PaymentFactory
{
    /**
     * Create a payment gateway instance dynamically.
     *
     * @param string $gateway The payment gateway identifier (e.g., 'stripe', 'paypal' etc.).
     * @param array $data The required parameters for the payment instance.
     * @return Payment
     * @throws Exception
     */
    public static function create(string $gateway, array $data): Payment
    {
        return match ($gateway) {
            'stripe' => new StripePayment(...$data),
            'paypal' => new PaypalPayment(...$data),
            default => throw new Exception("Invalid payment gateway: $gateway"),
        };
    }
}