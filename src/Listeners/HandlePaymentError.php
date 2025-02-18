<?php

namespace Emincmg\PaymentProcessorLaravel\Listeners;

use Emincmg\PaymentProcessorLaravel\Events\PaymentFailed;
use Emincmg\PaymentProcessorLaravel\Services\PaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandlePaymentError implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
    }

    public function handle(PaymentFailed $event)
    {
        $payment = $event->payment;
        Log::channel('payment_errors')->error('Payment failed', [
            'payment_id' => $payment->id ?? 'N/A',
            'amount' => $payment->amount ?? 'N/A',
            'currency' => $payment->currency ?? 'N/A',
            'payment_method' => $payment->payment_method ?? 'N/A',
            'status' => $payment->status ?? 'N/A',
            'error_message' => $event->exception->getMessage() ?? 'Unknown error',
            'timestamp' => now()->toDateTimeString(),
        ]);
        $payment->fail();
    }
}