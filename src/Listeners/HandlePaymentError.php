<?php

use Emincmg\PaymentProcessorLaravel\Services\PaymentService;
use Emincmg\PaymentProcessorLaravel\Events\PaymentSuccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandlePaymentError implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
    }

    public function handle(PaymentSuccess $event)
    {
        $payment = $event->payment;
        $payment->fail();
    }
}