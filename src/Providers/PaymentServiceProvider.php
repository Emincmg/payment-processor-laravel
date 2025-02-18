<?php

namespace Emincmg\PaymentProcessorLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
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
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/payment.php', 'payment'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/logging.php', 'logging'
        );
    }

    public function boot()
    {
        // publish config
        $this->publishes([
            __DIR__ . '/../../config/payment.php' => config_path('payment.php'),
        ], 'payment.config');
        $this->publishes([
            __DIR__ . '/../../config/logging.php' => config_path('logging.php'),
        ], 'payment.logging');

        // publish listeners
        $this->publishes([
            __DIR__ . '/../Listeners/HandlePaymentError.php' => $this->app()->path() . '/Listeners/HandlePaymentError.php',
        ], 'payment.listeners');
        $this->publishes([
            __DIR__ . '/../Listeners/HandlePaymentSuccess.php' => $this->app()->path() . '/Listeners/HandlePaymentSuccess.php',
        ], 'payment.listeners');

        // publish DTO's
        $this->publishes([
            __DIR__ . '/../Payments/DTO/PaymentRequestDTO.php' => $this->app()->path() . '/DTO/PaymentRequestDTO.php',
        ], 'payment.dto');
        $this->publishes([
            __DIR__ . '/../Payments/DTO/PaymentResponseDTO.php' => $this->app()->path() . '/DTO/PaymentResponseDTO.php',
        ], 'payment.dto');

        // publish factory
        $this->publishes([
            __DIR__ . '/../Payments/Factory/PaymentFactory.php' => $this->app()->path() . '/Factory/PaymentFactory.php',
        ], 'payment.factory');

        // publish strategies
        $this->publishes([
            __DIR__ . '/../Payments/Strategy/PaypalPayment.php' => $this->app()->path() . '/Strategy/PaypalPayment.php',
        ], 'payment.strategy');
        $this->publishes([
            __DIR__ . '/../Payments/Strategy/StripePayment.php' => $this->app()->path() . '/Strategy/StripePayment.php',
        ], 'payment.strategy');

        // publish exceptions
        $this->publishes([
            __DIR__ . '/../Exceptions/PaymentFailedException.php' => $this->app()->path() . '/Exceptions/PaymentFailedException.php',
        ], 'payment.exceptions');


        $now = now();
        $this->publishes([
            __DIR__ . '/../../migrations/create_payments_table.php' =>
                $this->app->databasePath('migrations' .
                    DIRECTORY_SEPARATOR . $now->format('Y_m_d_His') . '_create_payments_table.php'),
        ], 'migrations');
    }
}