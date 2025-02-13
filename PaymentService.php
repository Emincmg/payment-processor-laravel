<?php

use Emincmg\PaymentProcessorLaravel\Classes\Payments\StripePayment;
use Emincmg\PaymentProcessorLaravel\Classes\Payments\PaypalPayment;
use Emincmg\PaymentProcessorLaravel\Classes\Payments\Payment;
use Emincmg\PaymentProcessorLaravel\Factory\PaymentFactory;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PaymentService
{
    /**
     * Process a payment request.
     *
     * @param array $paymentData The payment data received from the controller.
     * @return void Process the payment algorithm.
     * @throws \Exception
     */
    public function processPayment(array $paymentData): void
    {
        $paymentInstance = $this->returnPaymentInstance($paymentData);
        $paymentInstance->process();
    }

    /**
     * Get the payment instance dynamically using the PaymentFactory.
     *
     * @param array $paymentData The raw payment data from the request.
     * @return StripePayment|PaypalPayment
     * @throws Exception
     */
    public function returnPaymentInstance(array $paymentData): StripePayment|PaypalPayment
    {
        if (!isset($paymentData['channel'])) {
            throw new Exception('Payment channel is required');
        }

        return PaymentFactory::create($paymentData['channel'], $paymentData);
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