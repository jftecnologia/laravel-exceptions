<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Auth;
use JuniorFontenele\LaravelExceptions\Providers\UserContextProvider;

uses(Tests\TestCase::class);

test('UserContextProvider returns user context when authenticated', function () {
    // Create a mock user
    $user = new class
    {
        public $id = 1;

        public $name = 'Test User';

        public $email = 'test@example.com';
    };

    Auth::shouldReceive('check')->andReturn(true);
    Auth::shouldReceive('id')->andReturn($user->id);
    Auth::shouldReceive('user')->andReturn($user);

    $provider = new UserContextProvider();
    $exception = new Exception('Test');

    $context = $provider->getContext($exception);

    expect($context)->toHaveKey('user')
        ->and($context['user']['id'])->toBe(1)
        ->and($context['user']['name'])->toBe('Test User')
        ->and($context['user']['email'])->toBe('test@example.com');
});

test('UserContextProvider should only run when user is authenticated', function () {
    $provider = new UserContextProvider();
    $exception = new Exception('Test');

    // Test when authenticated
    Auth::shouldReceive('check')->once()->andReturn(true);
    expect($provider->shouldRun($exception))->toBeTrue();
});

test('UserContextProvider should not run when user is not authenticated', function () {
    $provider = new UserContextProvider();
    $exception = new Exception('Test');

    // Test when not authenticated
    Auth::shouldReceive('check')->once()->andReturn(false);
    expect($provider->shouldRun($exception))->toBeFalse();
});
