<?php

namespace Rangkotodotcom\Simanang;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Rangkotodotcom\Simanang\Simanang;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Class SimanangServiceProvider
 * @package Rangkotodotcom\Simanang\Providers
 */
class SimanangServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/simanang.php' => config_path('simanang.php'),
            ], 'simanang-config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('simanang');
        }
    }

    public function register()
    {
        $this->app->bind('simanang-mode', function ($app) {
            return new Simanang(
                config('simanang.simanang_mode')
            );
        });

        $this->app->bind('simanang-client', function ($app) {
            return new Simanang(
                config('simanang.simanang_client_id')
            );
        });

        $this->app->bind('simanang-secret', function ($app) {
            return new Simanang(
                config('simanang.simanang_client_secret')
            );
        });
    }
}
