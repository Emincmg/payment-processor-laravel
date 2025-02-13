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
        $this->publishes([
            __DIR__ . '/../../config/payment.php' => config_path('payment.php'),
        ], 'payment.config');
        $this->publishes([
            __DIR__ . '/../../config/logging.php' => config_path('logging.php'),
        ], 'payment.logging');

        $now = now();
        $this->publishes([
            __DIR__ . '/../../migrations/create_payments_table.php' =>
                $this->app->databasePath('migrations' .
                    DIRECTORY_SEPARATOR . $now->format('Y_m_d_His') . '_create_payments_table.php'),
        ], 'migrations');
    }
}