<?php

declare(strict_types = 1);

return [
    'table_name' => 'exceptions_log',

    'model' => JuniorFontenele\LaravelExceptions\Models\ExceptionLog::class,

    'user_model' => config('auth.providers.users.model'),

    'context_providers' => [
        //
    ],

    'channels' => [
        JuniorFontenele\LaravelExceptions\Channels\Database::class,
    ],
];
