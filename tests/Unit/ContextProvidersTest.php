<?php

declare(strict_types = 1);

use JuniorFontenele\LaravelExceptions\Exceptions\AppException;
use JuniorFontenele\LaravelExceptions\Providers\AppContextProvider;
use JuniorFontenele\LaravelExceptions\Providers\AppExceptionContextProvider;
use JuniorFontenele\LaravelExceptions\Providers\ExceptionContextProvider;
use JuniorFontenele\LaravelExceptions\Providers\HostContextProvider;
use JuniorFontenele\LaravelExceptions\Providers\PreviousExceptionContextProvider;

uses(Tests\TestCase::class);

test('AppContextProvider returns app context', function () {
    $provider = new AppContextProvider();
    $exception = new Exception('Test');

    $context = $provider->getContext($exception);

    expect($context)->toHaveKey('app')
        ->and($context['app'])->toHaveKeys(['env', 'debug'])
        ->and($context['app']['env'])->toBeString()
        ->and($context['app']['debug'])->toBeBool();
});

test('AppContextProvider should always run', function () {
    $provider = new AppContextProvider();
    $exception = new Exception('Test');

    expect($provider->shouldRun($exception))->toBeTrue();
});

test('AppExceptionContextProvider returns error context for AppException', function () {
    $provider = new AppExceptionContextProvider();
    $exception = new AppException(
        message: 'Test error',
        userMessage: 'User message',
        statusCode: 422
    );

    $context = $provider->getContext($exception);

    expect($context)->toHaveKey('error')
        ->and($context['error'])->toHaveKeys(['id', 'user_message', 'status_code', 'is_retryable'])
        ->and($context['error']['user_message'])->toBe('User message')
        ->and($context['error']['status_code'])->toBe(422)
        ->and($context['error']['is_retryable'])->toBeFalse();
});

test('AppExceptionContextProvider returns empty for non AppException', function () {
    $provider = new AppExceptionContextProvider();
    $exception = new Exception('Test');

    $context = $provider->getContext($exception);

    expect($context)->toBe([]);
});

test('AppExceptionContextProvider should only run for AppException', function () {
    $provider = new AppExceptionContextProvider();

    expect($provider->shouldRun(new AppException()))->toBeTrue()
        ->and($provider->shouldRun(new Exception()))->toBeFalse();
});

test('ExceptionContextProvider returns exception details', function () {
    $provider = new ExceptionContextProvider();
    $exception = new Exception('Test message');

    $context = $provider->getContext($exception);

    expect($context)->toHaveKey('exception_detail')
        ->and($context['exception_detail'])->toHaveKeys(['class', 'message', 'file', 'line', 'code', 'stack_trace'])
        ->and($context['exception_detail']['class'])->toBe(Exception::class)
        ->and($context['exception_detail']['message'])->toBe('Test message');
});

test('ExceptionContextProvider should always run', function () {
    $provider = new ExceptionContextProvider();
    $exception = new Exception('Test');

    expect($provider->shouldRun($exception))->toBeTrue();
});

test('HostContextProvider returns host information', function () {
    $provider = new HostContextProvider();
    $exception = new Exception('Test');

    $context = $provider->getContext($exception);

    expect($context)->toHaveKey('host')
        ->and($context['host'])->toHaveKeys(['name', 'ip'])
        ->and($context['host']['name'])->toBeString()
        ->and($context['host']['ip'])->toBeString();
});

test('HostContextProvider should always run', function () {
    $provider = new HostContextProvider();
    $exception = new Exception('Test');

    expect($provider->shouldRun($exception))->toBeTrue();
});

test('PreviousExceptionContextProvider returns previous exception context', function () {
    $provider = new PreviousExceptionContextProvider();
    $previous = new Exception('Previous error');
    $exception = new Exception('Main error', 0, $previous);

    $context = $provider->getContext($exception);

    expect($context)->toHaveKey('previous_exception')
        ->and($context['previous_exception'])->toHaveKeys(['class', 'message', 'file', 'line', 'code', 'stack_trace'])
        ->and($context['previous_exception']['class'])->toBe(Exception::class)
        ->and($context['previous_exception']['message'])->toBe('Previous error');
});

test('PreviousExceptionContextProvider should only run when previous exception exists', function () {
    $provider = new PreviousExceptionContextProvider();
    $exceptionWithPrevious = new Exception('Main', 0, new Exception('Previous'));
    $exceptionWithoutPrevious = new Exception('Main');

    expect($provider->shouldRun($exceptionWithPrevious))->toBeTrue()
        ->and($provider->shouldRun($exceptionWithoutPrevious))->toBeFalse();
});
