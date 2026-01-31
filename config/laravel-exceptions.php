<?php

declare(strict_types = 1);

return [
    'view' => 'laravel-exceptions::error',

    'context_providers' => [
        JuniorFontenele\LaravelExceptions\Providers\ExceptionContextProvider::class,
    ],

    'channels' => [
        'database' => JuniorFontenele\LaravelExceptions\Channels\Database::class,
    ],

    'channels_settings' => [
        'database' => [
            'table_name' => 'exceptions_log',
            'model' => JuniorFontenele\LaravelExceptions\Models\Exception::class,
            'user_model' => config('auth.providers.users.model'),
        ],
    ],

    'ignored_exceptions' => [
        Illuminate\Auth\AuthenticationException::class,
        Illuminate\Validation\ValidationException::class,
    ],
];
