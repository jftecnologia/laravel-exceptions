<?php

declare(strict_types = 1);

return [
    'view' => 'laravel-exceptions::error',

    'convert_exceptions' => env('LARAVEL_EXCEPTIONS_CONVERT_EXCEPTIONS', true),

    'render_in_debug' => env('LARAVEL_EXCEPTIONS_RENDER_IN_DEBUG', false),

    'delete_records_older_than_days' => 365,

    'context_providers' => [
        JuniorFontenele\LaravelExceptions\Providers\AppExceptionContextProvider::class,
        JuniorFontenele\LaravelExceptions\Providers\AppContextProvider::class,
        JuniorFontenele\LaravelExceptions\Providers\HostContextProvider::class,
        JuniorFontenele\LaravelExceptions\Providers\UserContextProvider::class,
        JuniorFontenele\LaravelExceptions\Providers\ExceptionContextProvider::class,
        JuniorFontenele\LaravelExceptions\Providers\PreviousExceptionContextProvider::class,
    ],

    'channels' => [
        'database' => JuniorFontenele\LaravelExceptions\Channels\Database::class,
    ],

    'channels_settings' => [
        'database' => [
            'table_name' => 'exceptions_log',
            'model' => JuniorFontenele\LaravelExceptions\Models\Exception::class,
            'user_model' => config('auth.providers.users.model'),
            'user_model_table' => 'users',
        ],
    ],

    'ignored_exceptions' => [
        Illuminate\Auth\AuthenticationException::class,
        Illuminate\Validation\ValidationException::class,
    ],

    'http_exceptions' => [
        400 => JuniorFontenele\LaravelExceptions\Exceptions\Http\BadRequestHttpException::class,
        401 => JuniorFontenele\LaravelExceptions\Exceptions\Http\UnauthorizedHttpException::class,
        403 => JuniorFontenele\LaravelExceptions\Exceptions\Http\AccessDeniedHttpException::class,
        404 => JuniorFontenele\LaravelExceptions\Exceptions\Http\NotFoundHttpException::class,
        405 => JuniorFontenele\LaravelExceptions\Exceptions\Http\MethodNotAllowedHttpException::class,
        419 => JuniorFontenele\LaravelExceptions\Exceptions\Http\SessionExpiredHttpException::class,
        422 => JuniorFontenele\LaravelExceptions\Exceptions\Http\UnprocessableEntityHttpException::class,
        429 => JuniorFontenele\LaravelExceptions\Exceptions\Http\TooManyRequestsHttpException::class,
        500 => JuniorFontenele\LaravelExceptions\Exceptions\Http\InternalServerErrorHttpException::class,
        503 => JuniorFontenele\LaravelExceptions\Exceptions\Http\ServiceUnavailableHttpException::class,
        504 => JuniorFontenele\LaravelExceptions\Exceptions\Http\GatewayTimeoutHttpException::class,
    ],
];
