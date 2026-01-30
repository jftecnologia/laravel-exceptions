<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions;

use Illuminate\Support\ServiceProvider;
use JuniorFontenele\LaravelExceptions\Console\Commands\InstallCommand;
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

        $this->publishesMigrations([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'laravel-exceptions-migrations');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-exceptions');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-exceptions'),
        ], 'laravel-exceptions-views');

        $this->publishes([
            __DIR__ . '/../resources/dist/css/app.css' => public_path('vendor/juniorfontenele/laravel-exceptions/css/app.css'),
        ], 'laravel-exceptions-assets');

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'laravel-exceptions');

        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/laravel-exceptions'),
        ], 'laravel-exceptions-translations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-exceptions.php',
            'laravel-exceptions'
        );

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->app->bind(ExceptionModel::class, function ($app) {
            $exceptionModel = $app['config']->get('laravel-exceptions.model', ExceptionLog::class);

            return $app->make($exceptionModel);
        });
    }
}
