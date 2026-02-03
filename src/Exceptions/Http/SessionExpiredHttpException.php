<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Exceptions\Http;

use JuniorFontenele\LaravelExceptions\Exceptions\HttpException;
use Throwable;

class SessionExpiredHttpException extends HttpException
{
    public function __construct(
        string $message = '',
        int|string $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $userMessage = '',
        int $statusCode = 440,
    ) {
        $message = $message ?: __('laravel-exceptions::exceptions.system.http.session_expired');
        $userMessage = $userMessage ?: __('laravel-exceptions::exceptions.user.http.session_expired');

        parent::__construct($message, $code, $previous, $context, $userMessage, $statusCode);
    }
}
