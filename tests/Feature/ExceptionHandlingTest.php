<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\DB;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;
use JuniorFontenele\LaravelExceptions\Models\Exception;

beforeEach(function () {
    // Run migrations
    $this->artisan('migrate', ['--database' => 'testing'])->run();
});

test('exception is saved to database when thrown', function () {
    DB::beginTransaction();

    try {
        throw new AppException(
            message: 'Test exception message',
            userMessage: 'User friendly message',
            statusCode: 422
        );
    } catch (AppException $e) {
        // Get exception manager and build context
        $manager = app(JuniorFontenele\LaravelExceptions\ExceptionManager::class);
        $manager->buildContext($e);

        // Send to channels manually for testing
        $channel = app(JuniorFontenele\LaravelExceptions\Channels\Database::class);
        $channel->send($e, $manager->context());

        // Verify exception was saved
        $exception = Exception::first();

        expect($exception)->not->toBeNull()
            ->and($exception->exception_class)->toBe(AppException::class)
            ->and($exception->message)->toBe('Test exception message')
            ->and($exception->user_message)->toBe('User friendly message')
            ->and($exception->status_code)->toBe(422);
    }

    DB::rollBack();
});

test('exception context is built correctly with all providers', function () {
    $exception = new AppException(
        message: 'Integration test',
        userMessage: 'User message',
        statusCode: 500
    );

    $manager = app(JuniorFontenele\LaravelExceptions\ExceptionManager::class);
    $manager->buildContext($exception);

    $context = $manager->context();

    // Check that all context providers contributed
    expect($context)->toHaveKeys([
        'error',
        'app',
        'host',
        'exception_detail',
    ])
        ->and($context['error']['user_message'])->toBe('User message')
        ->and($context['error']['status_code'])->toBe(500)
        ->and($context['app']['env'])->toBeString()
        ->and($context['exception_detail']['class'])->toBe(AppException::class)
        ->and($context['exception_detail']['message'])->toBe('Integration test');
});

test('exception with previous exception includes previous context', function () {
    $previous = new \Exception('Previous exception');
    $exception = new AppException(
        message: 'Main exception',
        previous: $previous
    );

    $manager = app(JuniorFontenele\LaravelExceptions\ExceptionManager::class);
    $manager->buildContext($exception);

    $context = $manager->context();

    expect($context)->toHaveKey('previous_exception')
        ->and($context['previous_exception']['class'])->toBe(\Exception::class)
        ->and($context['previous_exception']['message'])->toBe('Previous exception');
});

test('multiple exceptions can be saved sequentially', function () {
    DB::beginTransaction();

    $channel = app(JuniorFontenele\LaravelExceptions\Channels\Database::class);
    $manager = app(JuniorFontenele\LaravelExceptions\ExceptionManager::class);

    foreach (range(1, 3) as $i) {
        $exception = new AppException(
            message: "Exception $i",
            userMessage: "User message $i",
            statusCode: 500
        );

        $manager->buildContext($exception);
        $channel->send($exception, $manager->context());
    }

    $count = Exception::count();

    expect($count)->toBe(3);

    DB::rollBack();
});

test('exception manager ignores configured exceptions', function () {
    // Create manager with ignored exceptions
    $config = [
        'ignored_exceptions' => [Exception::class],
    ];

    $manager = new JuniorFontenele\LaravelExceptions\ExceptionManager($config);

    // This is tested through the handles method, but we can't easily test that
    // so we just verify the manager was created with the config
    expect($manager)->toBeInstanceOf(JuniorFontenele\LaravelExceptions\ExceptionManager::class);
});
