<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use JuniorFontenele\LaravelExceptions\Channels\Database;
use JuniorFontenele\LaravelExceptions\Console\Commands\AppExceptionMakeCommand;
use JuniorFontenele\LaravelExceptions\Console\Commands\InstallCommand;
use JuniorFontenele\LaravelExceptions\Models\Exception;
use Psr\Log\LoggerInterface;

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

        $this->publishes([
            __DIR__ . '/../stubs/app-exception.stub' => base_path('stubs/app-exception.stub'),
        ], 'laravel-exceptions-stubs');

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'laravel-exceptions');

        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/laravel-exceptions'),
        ], 'laravel-exceptions-translations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                AppExceptionMakeCommand::class,
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

        $this->app->singleton(function (Application $app): Database {
            $exceptionModelClass = $app['config']->get('laravel-exceptions.channels_settings.database.model', Exception::class);

            $exceptionModel = $app->make($exceptionModelClass);

            return new Database(
                exceptionModel: $exceptionModel,
                logger: $app->make(LoggerInterface::class),
            );
        });

        $this->app->singleton(function (Application $app): ExceptionManager {
            $shouldConvertExceptions = $app['config']->boolean('laravel-exceptions.convert_exceptions', true);
            $shouldRenderInDebug = $app['config']->boolean('laravel-exceptions.render_in_debug', false);
            $ignoredExceptions = $app['config']->get('laravel-exceptions.ignored_exceptions', []);
            $errorView = $app['config']->get('laravel-exceptions.view', 'laravel-exceptions::error');
            $contextProviders = $app['config']->get('laravel-exceptions.context_providers', []);
            $channels = $app['config']->get('laravel-exceptions.channels', []);
            $config = $app['config']->get('laravel-exceptions', []);

            $manager = new ExceptionManager($config);

            foreach ($contextProviders as $providerClass) {
                $provider = $app->make($providerClass);
                $manager->addContextProvider($provider);
            }

            foreach ($channels as $channelClass) {
                $channel = $app->make($channelClass);
                $manager->addChannel($channel);
            }

            return $manager;
        });
    }
}
