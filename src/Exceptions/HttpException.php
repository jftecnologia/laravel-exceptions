<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Exceptions;

use Throwable;

class HttpException extends AppException
{
    public function __construct(
        string $message = '',
        int|string $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $userMessage = '',
        int $statusCode = 500,
    ) {
        $message = $message ?: __('laravel-exceptions::exceptions.system.http.internal_server_error');
        $userMessage = $userMessage ?: __('laravel-exceptions::exceptions.user.http.internal_server_error');

        parent::__construct($message, $code, $previous, $context, $userMessage, $statusCode);
    }
}
