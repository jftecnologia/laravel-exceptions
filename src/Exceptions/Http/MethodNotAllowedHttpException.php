<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Exceptions\Http;

use JuniorFontenele\LaravelExceptions\Exceptions\HttpException;
use Throwable;

class MethodNotAllowedHttpException extends HttpException
{
    public function __construct(
        string $message = '',
        int|string $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $userMessage = '',
        int $statusCode = 405,
    ) {
        $message = $message ?: __('laravel-exceptions::exceptions.system.http.method_not_allowed');
        $userMessage = $userMessage ?: __('laravel-exceptions::exceptions.user.http.method_not_allowed');

        parent::__construct($message, $code, $previous, $context, $userMessage, $statusCode);
    }
}
