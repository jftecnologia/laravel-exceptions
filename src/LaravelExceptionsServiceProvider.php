<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions;

use Illuminate\Support\ServiceProvider;
use JuniorFontenele\LaravelExceptions\Models\ExceptionLog;
use JuniorFontenele\LaravelExceptions\Models\ExceptionModel;

class LaravelExceptionsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-exceptions.php' => config_path('laravel-exceptions.php'),
        ], 'laravel-exceptions-config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-exceptions.php', 'laravel-exceptions');

        $this->app->bind(ExceptionModel::class, function ($app) {
            $exceptionModel = $app['config']->get('laravel-exceptions.model', ExceptionLog::class);

            return $app->make($exceptionModel);
        });
    }
}
