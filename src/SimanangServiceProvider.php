<?php

namespace Rangkotodotcom\Simanang;

use Rangkotodotcom\Simanang\Simanang;
use Illuminate\Support\ServiceProvider;
use Rangkotodotcom\Simanang\Networks\HttpClient;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Foundation\Application as LaravelApplication;

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
            ], 'config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('simanang');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/simanang.php', 'simanang');

        $this->app->singleton('simanang', function () {
            return new Simanang(new HttpClient(config('simanang.simanang_mode'), config('simanang.simanang_client_id'), config('simanang.simanang_client_secret')));
        });
    }
}
