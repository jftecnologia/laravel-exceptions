<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Carbon;

class ExceptionCleanCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'laravel-exceptions:clean
                            {--days= : (optional) Records older than this number of days will be deleted.}
                            {--force : (optional) Force the operation to run in production.}';

    protected $description = 'Clean old exception records from the database';

    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $this->info('Cleaning old exception records...');

        $maxAgeInDays = $this->option('days') ?? config('laravel-exceptions.delete_records_older_than_days', 365);

        $cutoffDate = Carbon::now()->subDays((int) $maxAgeInDays)->format('Y-m-d H:i:s');

        $modelClass = config('laravel-exceptions.channels_settings.database.model');

        $modelInstance = app()->make($modelClass);

        $ammountDeleted = $modelInstance->where('created_at', '<', $cutoffDate)->delete();

        $this->info("Deleted {$ammountDeleted} exception record(s) older than {$maxAgeInDays} days.");

        $this->info('Done.');
    }
}
