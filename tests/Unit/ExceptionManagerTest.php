<?php

declare(strict_types = 1);

use JuniorFontenele\LaravelExceptions\Contracts\ExceptionChannel;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use JuniorFontenele\LaravelExceptions\ExceptionManager;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;

uses(Tests\TestCase::class);

test('ExceptionManager can add context providers', function () {
    $manager = new ExceptionManager();

    $provider = Mockery::mock(ExceptionContext::class);

    $result = $manager->addContextProvider($provider);

    expect($result)->toBeInstanceOf(ExceptionManager::class);
});

test('ExceptionManager can add channels', function () {
    $manager = new ExceptionManager();

    $channel = Mockery::mock(ExceptionChannel::class);

    $result = $manager->addChannel($channel);

    expect($result)->toBeInstanceOf(ExceptionManager::class);
});

test('ExceptionManager builds context from providers', function () {
    $manager = new ExceptionManager();

    $provider1 = Mockery::mock(ExceptionContext::class);
    $provider1->shouldReceive('shouldRun')->andReturn(true);
    $provider1->shouldReceive('getContext')->andReturn(['key1' => 'value1']);

    $provider2 = Mockery::mock(ExceptionContext::class);
    $provider2->shouldReceive('shouldRun')->andReturn(true);
    $provider2->shouldReceive('getContext')->andReturn(['key2' => 'value2']);

    $manager->addContextProvider($provider1)
        ->addContextProvider($provider2);

    $exception = new Exception('Test');
    $manager->buildContext($exception);

    $context = $manager->context();

    expect($context)->toBe([
        'key1' => 'value1',
        'key2' => 'value2',
    ]);
});

test('ExceptionManager only runs providers that should run', function () {
    $manager = new ExceptionManager();

    $provider1 = Mockery::mock(ExceptionContext::class);
    $provider1->shouldReceive('shouldRun')->andReturn(true);
    $provider1->shouldReceive('getContext')->andReturn(['key1' => 'value1']);

    $provider2 = Mockery::mock(ExceptionContext::class);
    $provider2->shouldReceive('shouldRun')->andReturn(false);
    $provider2->shouldNotReceive('getContext');

    $manager->addContextProvider($provider1)
        ->addContextProvider($provider2);

    $exception = new Exception('Test');
    $manager->buildContext($exception);

    $context = $manager->context();

    expect($context)->toBe(['key1' => 'value1']);
});

test('ExceptionManager returns empty context before building', function () {
    $manager = new ExceptionManager();

    expect($manager->context())->toBe([]);
});

test('ExceptionManager merges context from multiple providers', function () {
    $manager = new ExceptionManager();

    $provider1 = Mockery::mock(ExceptionContext::class);
    $provider1->shouldReceive('shouldRun')->andReturn(true);
    $provider1->shouldReceive('getContext')->andReturn([
        'app' => ['env' => 'testing'],
        'user' => ['id' => 1],
    ]);

    $provider2 = Mockery::mock(ExceptionContext::class);
    $provider2->shouldReceive('shouldRun')->andReturn(true);
    $provider2->shouldReceive('getContext')->andReturn([
        'host' => ['name' => 'localhost'],
    ]);

    $manager->addContextProvider($provider1)
        ->addContextProvider($provider2);

    $exception = new AppException('Test');
    $manager->buildContext($exception);

    $context = $manager->context();

    expect($context)->toHaveKeys(['app', 'user', 'host']);
});

afterEach(function () {
    Mockery::close();
});
