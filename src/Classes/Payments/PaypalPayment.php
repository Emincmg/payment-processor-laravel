<?php

use Emincmg\PaymentProcessorLaravel\Events\PaymentFailed;
use Emincmg\PaymentProcessorLaravel\Events\PaymentSuccess;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Emincmg\PaymentProcessorLaravel\Exceptions\PaymentFailedException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Customer;

/**
 * Class StripePayment
 *
 * Handles Stripe payment processing using Laravel's event system.
 */
class PaypalPayment extends Payment
{
    /**
     * @var PaymentIntent The Stripe payment intent instance.
     */
    private PaymentIntent $paymentIntent;

    /**
     * @var Customer The Stripe customer instance.
     */
    private Customer $customer;

    /**
     * StripePayment constructor.
     *
     * Initializes Stripe API with the secret key from config.
     */
    public function __construct()
    {
        Stripe::setApiKey(config('payment.stripe.secret'));
    }

    /**
     * Processes the payment by creating a payment intent
     * and triggering the appropriate event.
     *
     * @return void
     */
    public function process(): void
    {
        Event::dispatch(new PaymentStarted($this));

        $this->createPaymentIntent();

        if ($this->paymentIntent->status !== 'requires_payment_method') {
            $this->success();
        } else {
            $this->fail();
        }
    }

    /**
     * Creates a Stripe customer based on user information.
     *
     * @return void
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createCustomer(): void
    {
        $this->customer = Customer::create([
            'name' => $this->userName,
            'email' => $this->userEmail,
            'phone' => $this->userPhone,
        ]);
    }

    /**
     * Creates a payment intent using the Stripe API.
     *
     * @return void
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createPaymentIntent(): void
    {
        $this->paymentIntent = PaymentIntent::create([
            'customer' => $this->customer->id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_method' => $this->paymentMethod,
            'confirmation_method' => $this->confirmationMethod,
            'confirm' => true,
            'return_url' => config('payment.stripe.return_url'),
            'payment_method_options' => [
                'card' => [
                    'request_three_d_secure' => 'any',
                ],
            ],
        ]);

        Event::dispatch(new PaymentIntentCreated($this->paymentIntent));
    }

    /**
     * Handles a failed payment.
     *
     * @return void
     */
    public function fail(): void
    {
        $exception = new PaymentFailedException('Error processing payment');
        Event::dispatch(new PaymentFailed($exception));
    }

    /**
     * Handles a successful payment by updating
     * the database and logging the success.
     *
     * @return void
     */
    public function success(): void
    {
        try {
            DB::transaction(function () {
                $this->confirmed_at = now();
                $this->status = 'confirmed';
                $this->save();
            });
            Log::channel('payment_success')->info('Stripe payment success!');
            Event::dispatch(new PaymentSuccess($this));
        } catch (PaymentFailedException $exception) {
            Event::dispatch(new PaymentFailed($exception));
        }
    }

    /**
     * Retrieves the Stripe payment intent instance.
     *
     * @return PaymentIntent
     */
    public function getPaymentIntent(): PaymentIntent
    {
        return $this->paymentIntent;
    }
}
