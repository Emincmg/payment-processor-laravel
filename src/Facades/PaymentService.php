<?php

namespace Emincmg\LaravelPaymentService\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void processPayment ($paymentDTO)
 * @method static \Emincmg\PaymentProcessorLaravel\Payment returnPaymentInstance ($paymentDTO)
 * @method static void handleFailure ($payment)
 * @method static void handleSuccess ($payment)
 * @method static void associateUser ($user,$payment)
 */
class PaymentService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'payment-service';
    }
}