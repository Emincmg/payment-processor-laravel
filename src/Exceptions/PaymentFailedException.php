<?php

namespace Emincmg\PaymentProcessorLaravel\Exceptions;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;

class PaymentFailedException extends Exception
{
    protected Payment $payment;

    public function __construct($message = "Payment processing failed", $payment = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->payment = $payment;

        // Log the error using a dedicated Stripe error channel
        Log::channel('payment_error')->error($message, [
            'payment' => $this->payment,
            'exception' => $this->getMessage(),
        ]);
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }
}
