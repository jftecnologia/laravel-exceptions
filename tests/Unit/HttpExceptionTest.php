<?php

declare(strict_types = 1);

use JuniorFontenele\LaravelExceptions\Exceptions\HttpException;

uses(Tests\TestCase::class);

test('HttpException creates with default values', function () {
    $exception = new HttpException();

    expect($exception->getMessage())->toBeString()
        ->and($exception->getUserMessage())->toBeString()
        ->and($exception->getStatusCode())->toBe(500);
});

test('HttpException can be created with custom values', function () {
    $exception = new HttpException(
        message: 'Custom http error',
        code: 9001,
        userMessage: 'Custom user message',
        statusCode: 404,
    );

    expect($exception->getMessage())->toBe('Custom http error')
        ->and($exception->getCode())->toBe(9001)
        ->and($exception->getUserMessage())->toBe('Custom user message')
        ->and($exception->getStatusCode())->toBe(404);
});

test('HttpException is an instance of AppException', function () {
    $exception = new HttpException();

    expect($exception)->toBeInstanceOf(JuniorFontenele\LaravelExceptions\Exceptions\AppException::class);
});

test('HttpException can have context', function () {
    $exception = new HttpException(
        message: 'Error',
        context: ['request_id' => '123']
    );

    expect($exception->context())->toBe(['request_id' => '123']);
});
