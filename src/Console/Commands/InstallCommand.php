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

        $this->info('Laravel Exceptions package installed successfully.');
    }
}
