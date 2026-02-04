<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\DB;
use JuniorFontenele\LaravelExceptions\Models\Exception;

beforeEach(function () {
    $this->artisan('migrate', ['--database' => 'testing'])->run();
});

test('exception model uses configured table name', function () {
    $exception = new Exception();

    $tableName = $exception->getTable();

    expect($tableName)->toBe(config('laravel-exceptions.channels_settings.database.table_name', 'exceptions'));
});

test('exception model casts attributes correctly', function () {
    DB::beginTransaction();

    $exception = Exception::create([
        'exception_class' => 'Exception',
        'message' => 'Test',
        'is_retryable' => true,
    ]);

    expect($exception->is_retryable)->toBeTrue();

    DB::rollBack();
});

test('exception model is unguarded', function () {
    DB::beginTransaction();

    // Should be able to mass assign any field
    $exception = Exception::create([
        'exception_class' => 'TestException',
        'message' => 'Test message',
        'file' => '/path/to/file.php',
        'line' => 42,
        'code' => 1001,
        'status_code' => 500,
    ]);

    expect($exception->exception_class)->toBe('TestException')
        ->and($exception->message)->toBe('Test message')
        ->and($exception->line)->toBe(42);

    DB::rollBack();
});
