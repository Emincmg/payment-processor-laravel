<?php

namespace Emincmg\PaymentProcessorLaravel;

use App\Models\User;
use DB;
use Emincmg\PaymentProcessorLaravel\Classes\Payments\Payment;
use Emincmg\PaymentProcessorLaravel\Classes\Payments\PaypalPayment;
use Emincmg\PaymentProcessorLaravel\Classes\Payments\StripePayment;
use Emincmg\PaymentProcessorLaravel\Payments\DTO\PaymentRequestDTO;
use Emincmg\PaymentProcessorLaravel\Payments\Factory\PaymentFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PaymentService
{
    /**
     * Process a payment request.
     *
     * @param PaymentRequestDTO $paymentDTO The payment data received from the controller.
     * @return void Process the payment algorithm.
     * @throws \Exception
     */
    public function processPayment(PaymentRequestDTO $paymentDTO): void
    {
        $paymentInstance = $this->returnPaymentInstance($paymentDTO);
        $paymentInstance->process();
    }

    /**
     * Get the payment instance dynamically using the PaymentFactory.
     *
     * @param PaymentRequestDTO $paymentDTO The raw payment data from the request.
     * @return Payment
     * @throws Exception
     */
    public function returnPaymentInstance(PaymentRequestDTO $paymentDTO): Payment
    {
        if (!isset($paymentDTO->channel)) {
            throw new Exception('Payment channel is required');
        }

        return PaymentFactory::create($paymentDTO->channel, (array) $paymentDTO);
    }

    /**
     * Handle payment failure.
     *
     * @param Payment $payment
     * @return void
     */
    public function handleFailure(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment->status = 'declined';
            $payment->declined_at = now();
            $payment->save();
        });
    }

    /**
     * Handle payment success.
     *
     * @param Payment $payment
     * @return void
     */
    public function handleSuccess(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $payment->status = 'paid';
            $payment->confirmed_at = now();
            $payment->save();
        });

    }

    /**
     * Associate payment with user.
     *
     * @param User $user
     * @param Payment $payment
     * @return void
     */
    public function associateUser(User $user, Payment $payment): void
    {
        DB::transaction(function () use ($user, $payment) {
            $user->payments()->save($payment);
        });
    }
}