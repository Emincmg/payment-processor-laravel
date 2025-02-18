# Payment Processor Laravel

**Payment Processor Laravel** is a package designed for quick and secure integration of various payment services into Laravel applications.

## Features

- **Modular Payment Integrations**: Easily integrate multiple payment gateways.
- **Supports PayPal & Stripe Out of the Box**: PayPal & Stripe implemetion comes already set up.
- **Uses DTO's**: Uses Data Transfer Objects for structural design purposes.
- **Strategy Pattern**: Implements the Strategy Pattern for flexible payment processing.
- **Event-Driven Architecture**: Leverage Laravel's event system for payment notifications.
- **Extensible Design**: Simplify the addition of new payment providers thanks to Strategy Design Pattern.

## Installation

To install the package, use Composer:

```bash
composer require emincmg/payment-processor-laravel
```

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="Emincmg\PaymentProcessorLaravel\Providers\PaymentServiceProvider"
```

This command will publish the `payment.php` configuration file to your application's `config` directory.

## Configuration

Configure your payment gateways in the `config/payment.php` file. For example:

```php
return [

    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'return_url' => env('STRIPE_RETURN_URL', 'http://your-app/return-url'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        'return_url' => env('PAYPAL_RETURN_URL', 'https://your-app.com/payment/success'),
        'cancel_url' => env('PAYPAL_CANCEL_URL', 'https://your-app.com/payment/cancel'),
        'log_enabled' => env('PAYPAL_LOG_ENABLED', true),
        'log_level' => env('PAYPAL_LOG_LEVEL', 'ERROR'),
    ],

];
```

Ensure you set the corresponding environment variables in your `.env` file:

```env
STRIPE_SECRET=your-stripe-secret-key
STRIPE_RETURN_URL=https://your-app.com/stripe/return

PAYPAL_CLIENT_ID=your-paypal-client-id
PAYPAL_SECRET=your-paypal-secret
PAYPAL_MODE=sandbox
PAYPAL_RETURN_URL=https://your-app.com/paypal/success
PAYPAL_CANCEL_URL=https://your-app.com/paypal/cancel
PAYPAL_LOG_ENABLED=true
PAYPAL_LOG_LEVEL=ERROR
```

## Usage

Simple controller example for easy integration:

```php
use Emincmg\PaymentProcessorLaravel\Requests\PaymentCreateRequest;
use Emincmg\PaymentProcessorLaravel\Facades\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{

    public function initiatePayment(PaymentCreateRequest $request): JsonResponse
    {
        // create dto instance from formrequests.
        $paymentDTO = $request->toDTO();
        
        // pass the created instance to the service and it will return the response dto
        $responseDTO = PaymentService::processPayment($paymentDTO);

        return response()->json([
            'status' => $responseDTO->status,
            'payment' => $responseDTO->toJson()],200);
    }
}

```

## Events

**Package is tailored with async workflow in mind, you should work with these events and approve payments via dispatching `PaymentSuccess` event!**


The package dispatches the following events during the payment process:

- `PaymentStarted`: Dispatched when a payment process starts.
- `PaymentSuccess`: Dispatched upon successful payment completion.
- `PaymentFailed`: Dispatched if the payment process fails.
- `PaymentIntentCreated`: Dispatched when Stripe payment intent is created successfully.

You can listen to these events in your application's event listeners to perform actions like sending notifications or updating order statuses.
## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

---

