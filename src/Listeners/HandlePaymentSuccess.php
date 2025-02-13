<?php

use Emincmg\PaymentProcessorLaravel\Services\PaymentService;
use Emincmg\PaymentProcessorLaravel\Events\PaymentSuccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandlePaymentSuccess implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(public PaymentService $paymentService)
    {
    }

    public function handle(PaymentSuccess $event)
    {
        $payment = $event->payment;
        $payment->success();
    }
}