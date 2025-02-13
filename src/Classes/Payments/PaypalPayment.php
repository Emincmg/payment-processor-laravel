<?php

namespace Emincmg\PaymentProcessorLaravel\Models;

use Emincmg\PaymentProcessorLaravel\Events\PaymentFailed;
use Emincmg\PaymentProcessorLaravel\Events\PaymentSuccess;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Emincmg\PaymentProcessorLaravel\Exceptions\PaymentFailedException;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Payer;
use PayPal\Api\Transaction;
use PayPal\Api\Amount;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Capture;
use PayPal\Api\Authorization;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

/**
 * Class PaypalPayment
 *
 * Handles PayPal payment processing.
 */
class PaypalPayment extends Payment
{
    /**
     * @var ApiContext PayPal API context instance.
     */
    private ApiContext $apiContext;

    /**
     * PaypalPayment constructor.
     *
     * Initializes PayPal API credentials.
     */
    public function __construct()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('payment.paypal.client_id'),
                config('payment.paypal.secret')
            )
        );
    }

    /**
     * Create a PayPal payment and return the approval URL.
     *
     * @return string The PayPal approval URL.
     * @throws \Exception
     */
    public function process(): string
    {
        try {
            $payer = new Payer();
            $payer->setPaymentMethod("paypal");

            $amount = new Amount();
            $amount->setTotal($this->amount);
            $amount->setCurrency($this->currency);

            $transaction = new Transaction();
            $transaction->setAmount($amount);
            $transaction->setDescription("Payment for Order #" . $this->id);

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(config('payment.paypal.return_url'))
                ->setCancelUrl(config('payment.paypal.cancel_url'));

            $payment = new Payment();
            $payment->setIntent("sale")
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls);

            $payment->create($this->apiContext);

            return $payment->getApprovalLink(); // Redirect user to this URL
        } catch (\Exception $e) {
            $this->fail();
            throw new PaymentFailedException("PayPal Payment Failed: " . $e->getMessage());
        }
    }

    /**
     * Executes a PayPal payment after user approval.
     *
     * @param string $paymentId The PayPal payment ID.
     * @param string $payerId The PayPal payer ID.
     * @return bool
     * @throws \Exception
     */
    public function executePayment(string $paymentId, string $payerId): bool
    {
        try {
            $payment = Payment::get($paymentId, $this->apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);

            $result = $payment->execute($execution, $this->apiContext);

            if ($result->getState() === "approved") {
                $this->success();
                return true;
            } else {
                $this->fail();
                return false;
            }
        } catch (\Exception $e) {
            $this->fail();
            throw new PaymentFailedException("PayPal Execution Failed: " . $e->getMessage());
        }
    }

    /**
     * Handles a failed payment.
     *
     * @return void
     */
    public function fail(): void
    {
        Event::dispatch(new PaymentFailed($this));
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
            Log::channel('payment_success')->info('PayPal payment success!');
            Event::dispatch(new PaymentSuccess($this));
        } catch (PaymentFailedException $exception) {
            Event::dispatch(new PaymentFailed($exception));
        }
    }
}
