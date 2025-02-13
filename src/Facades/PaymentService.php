<?php

namespace Emincmg\LaravelPaymentService\Facades;

use Illuminate\Support\Facades\Facade;
class PaymentService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'payment-service';
    }
}