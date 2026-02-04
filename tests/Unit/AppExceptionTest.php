<?php

declare(strict_types = 1);

use JuniorFontenele\LaravelExceptions\Exceptions\AppException;

uses(Tests\TestCase::class);

test('AppException creates an exception with default values', function () {
    $exception = new AppException();

    expect($exception->getMessage())->toBeString()
        ->and($exception->getUserMessage())->toBeString()
        ->and($exception->getStatusCode())->toBe(500)
        ->and($exception->getErrorId())->toBeString()
        ->and($exception->context())->toBe([])
        ->and($exception->isRetryable())->toBeFalse();
});

test('AppException can be created with custom values', function () {
    $exception = new AppException(
        message: 'Custom error message',
        code: 1001,
        context: ['key' => 'value'],
        userMessage: 'User friendly message',
        statusCode: 422,
    );

    expect($exception->getMessage())->toBe('Custom error message')
        ->and($exception->getCode())->toBe(1001)
        ->and($exception->getUserMessage())->toBe('User friendly message')
        ->and($exception->getStatusCode())->toBe(422)
        ->and($exception->context())->toBe(['key' => 'value']);
});

test('AppException can add context with withContext method', function () {
    $exception = new AppException(
        message: 'Test message',
        context: ['initial' => 'value']
    );

    $exception->withContext(['additional' => 'data']);

    expect($exception->context())->toBe([
        'initial' => 'value',
        'additional' => 'data',
    ]);
});

test('AppException generates unique error IDs', function () {
    $exception1 = new AppException();
    $exception2 = new AppException();

    expect($exception1->getErrorId())
        ->not->toBe($exception2->getErrorId());
});

test('AppException can chain withContext', function () {
    $exception = new AppException();

    $result = $exception->withContext(['key' => 'value']);

    expect($result)->toBeInstanceOf(AppException::class)
        ->and($exception->context())->toBe(['key' => 'value']);
});

test('AppException with previous exception', function () {
    $previous = new Exception('Previous error');
    $exception = new AppException(
        message: 'Main error',
        previous: $previous
    );

    expect($exception->getPrevious())->toBe($previous);
});
