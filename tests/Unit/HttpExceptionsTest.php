<?php

declare(strict_types = 1);

use JuniorFontenele\LaravelExceptions\Exceptions\Http\BadRequestHttpException;
use JuniorFontenele\LaravelExceptions\Exceptions\Http\NotFoundHttpException;
use JuniorFontenele\LaravelExceptions\Exceptions\Http\UnauthorizedHttpException;
use JuniorFontenele\LaravelExceptions\Exceptions\HttpException;

uses(Tests\TestCase::class);

test('BadRequestHttpException has correct status code', function () {
    $exception = new BadRequestHttpException();

    expect($exception->getStatusCode())->toBe(400)
        ->and($exception)->toBeInstanceOf(HttpException::class);
});

test('UnauthorizedHttpException has correct status code', function () {
    $exception = new UnauthorizedHttpException();

    expect($exception->getStatusCode())->toBe(401)
        ->and($exception)->toBeInstanceOf(HttpException::class);
});

test('NotFoundHttpException has correct status code', function () {
    $exception = new NotFoundHttpException();

    expect($exception->getStatusCode())->toBe(404)
        ->and($exception)->toBeInstanceOf(HttpException::class);
});

test('HTTP exceptions can be created with custom messages', function () {
    $exception = new NotFoundHttpException(
        message: 'Resource not found',
        userMessage: 'The page you are looking for does not exist'
    );

    expect($exception->getMessage())->toBe('Resource not found')
        ->and($exception->getUserMessage())->toBe('The page you are looking for does not exist')
        ->and($exception->getStatusCode())->toBe(404);
});
