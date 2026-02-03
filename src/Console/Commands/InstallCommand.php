<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'laravel-exceptions:install';

    protected $description = 'Install the Laravel Exceptions package';

    public function handle(): void
    {
        $this->info('Installing Laravel Exceptions package...');

        $this->call('vendor:publish', [
            '--provider' => 'JuniorFontenele\LaravelExceptions\LaravelExceptionsServiceProvider',
            '--tag' => 'laravel-exceptions-assets',
        ]);

        $this->confirm('Do you want to publish the migrations?', true) && $this->call('vendor:publish', [
            '--provider' => 'JuniorFontenele\LaravelExceptions\LaravelExceptionsServiceProvider',
            '--tag' => 'laravel-exceptions-migrations',
        ]);

        $this->confirm('Do you want to publish the configuration file?', true) && $this->call('vendor:publish', [
            '--provider' => 'JuniorFontenele\LaravelExceptions\LaravelExceptionsServiceProvider',
            '--tag' => 'laravel-exceptions-config',
        ]);

        $this->confirm('Do you want to run the migrations now?', false) && $this->call('migrate');

        $this->info('Laravel Exceptions package installed successfully.');
    }
}
