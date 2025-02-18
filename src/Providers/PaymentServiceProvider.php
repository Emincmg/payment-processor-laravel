<?php

namespace Emincmg\PaymentProcessorLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use Emincmg\PaymentProcessorLaravel\PaymentService;
use Emincmg\PaymentProcessorLaravel\Events\PaymentSuccess;
use Emincmg\PaymentProcessorLaravel\Listeners\HandlePaymentSuccess;

class PaymentServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentSuccess::class => [
            HandlePaymentSuccess::class,
        ],
    ];

    public function register()
    {
        $this->app->singleton('PaymentService', function () {
            return new PaymentService();
        });

        $this->mergeConfigFrom(__DIR__ . '/../../config/payment.php', 'payment');
        $this->mergeConfigFrom(__DIR__ . '/../../config/logging.php', 'logging');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Publish migrations with dynamic timestamp
            $timestamp = date('Y_m_d_His');
            $this->publishes([
                __DIR__ . '/../../migrations/create_payments_table.php' =>
                    database_path("migrations/{$timestamp}_create_payments_table.php"),
            ], 'migrations');

            // Publish configurations
            $this->publishes([
                __DIR__ . '/../../config/payment.php' => config_path('payment.php'),
                __DIR__ . '/../../config/logging.php' => config_path('logging.php'),
            ], 'payment.config');

            // Publish events & listeners
            $this->publishes([
                __DIR__ . '/../Listeners/HandlePaymentError.php' => $this->app->path('Listeners/HandlePaymentError.php'),
                __DIR__ . '/../Listeners/HandlePaymentSuccess.php' => $this->app->path('Listeners/HandlePaymentSuccess.php'),
                __DIR__ . '/../Events/Stripe/PaymentIntentCreated.php' => $this->app->path('Events/PaymentIntentCreated.php'),
                __DIR__ . '/../Events/PaymentStarted.php' => $this->app->path('Events/PaymentStarted.php'),
                __DIR__ . '/../Events/PaymentFailed.php' => $this->app->path('Events/PaymentFailed.php'),
                __DIR__ . '/../Events/PaymentSuccess.php' => $this->app->path('Events/PaymentSuccess.php'),
            ], 'payment.events');

            // Publish DTOs
            $this->publishes([
                __DIR__ . '/../Payments/DTO/PaymentRequestDTO.php' => $this->app->path('DTO/PaymentRequestDTO.php'),
                __DIR__ . '/../Payments/DTO/PaymentResponseDTO.php' => $this->app->path('DTO/PaymentResponseDTO.php'),
            ], 'payment.dto');

            // Publish Factory
            $this->publishes([
                __DIR__ . '/../Payments/Factory/PaymentFactory.php' => $this->app->path('Factory/PaymentFactory.php'),
            ], 'payment.factory');

            // Publish Strategies
            $this->publishes([
                __DIR__ . '/../Payments/Strategy/PaypalPayment.php' => $this->app->path('Strategy/PaypalPayment.php'),
                __DIR__ . '/../Payments/Strategy/StripePayment.php' => $this->app->path('Strategy/StripePayment.php'),
            ], 'payment.strategy');

            // Publish Exceptions
            $this->publishes([
                __DIR__ . '/../Exceptions/PaymentFailedException.php' => $this->app->path('Exceptions/PaymentFailedException.php'),
            ], 'payment.exceptions');
        }
    }
}
