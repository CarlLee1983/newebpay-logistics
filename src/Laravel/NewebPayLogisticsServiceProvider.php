<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Laravel;

use CarlLee\NewebPayLogistics\NewebPayLogistics;
use Illuminate\Support\ServiceProvider;

/**
 * Laravel Service Provider for NewebPay Logistics
 */
class NewebPayLogisticsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/newebpay-logistics.php',
            'newebpay-logistics'
        );

        $this->app->singleton(NewebPayLogistics::class, function ($app) {
            $config = $app['config']['newebpay-logistics'];

            return NewebPayLogistics::create(
                $config['merchant_id'],
                $config['hash_key'],
                $config['hash_iv'],
                $config['server_url'] ?? null
            );
        });

        $this->app->alias(NewebPayLogistics::class, 'newebpay-logistics');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/newebpay-logistics.php' => config_path('newebpay-logistics.php'),
            ], 'newebpay-logistics-config');
        }
    }
}
