# Payment Processor Laravel

**Payment Processor Laravel** is a package designed for quick and secure integration of various payment services into Laravel applications.

## Features

- **Modular Payment Integrations**: Easily integrate multiple payment gateways.
- **Event-Driven Architecture**: Leverage Laravel's event system for payment notifications.
- **Extensible Design**: Simplify the addition of new payment providers.

## Installation

To install the package, use Composer:

```bash
composer require emincmg/payment-processor-laravel
```

After installation, publish the configuration file:

```bash
php artisan vendor:publish --tag=payment-config
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

To process a payment using the facade:

```php
use Emincmg\PaymentProcessorLaravel\Facades\PaymentService;

$paymentData = [
    'channel' => 'stripe',
    'amount' => 1000,
    'currency' => 'USD',
    'userName' => 'John Doe',
    'userEmail' => 'john.doe@example.com',
    'userPhone' => '1234567890',
    'paymentMethod' => 'pm_card_visa',
    'confirmationMethod' => 'automatic',
];

$paymentInstance = PaymentService::returnPaymentInstance($paymentData);
$paymentInstance->process();
```

For PayPal payments:

```php
use Emincmg\PaymentProcessorLaravel\PaymentService;$paymentData['channel'] = 'paypal';
$paymentInstance = PaymentService::returnPaymentInstance($paymentData);
$approvalUrl = $paymentInstance->process();

// Redirect the user to $approvalUrl
```

After the user approves the PayPal payment:

```php
use Emincmg\PaymentProcessorLaravel\PaymentService;$paymentId = $request->query('paymentId');
$payerId = $request->query('PayerID');

$paymentData['channel'] = 'paypal';
$paymentInstance = PaymentService::returnPaymentInstance($paymentData);
$paymentInstance->executePayment($paymentId, $payerId);
```

## Events

The package dispatches the following events during the payment process:

- `PaymentStarted`: Dispatched when a payment process starts.
- `PaymentSuccess`: Dispatched upon successful payment completion.
- `PaymentFailed`: Dispatched if the payment process fails.

You can listen to these events in your application's event listeners to perform actions like sending notifications or updating order statuses.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

---

*Note: Replace placeholder values with your actual configuration details.*

