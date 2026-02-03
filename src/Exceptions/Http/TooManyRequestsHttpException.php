<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Exceptions\Http;

use JuniorFontenele\LaravelExceptions\Exceptions\HttpException;
use Throwable;

class TooManyRequestsHttpException extends HttpException
{
    public function __construct(
        string $resource,
        string $message = '',
        int|string $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $userMessage = '',
        int $statusCode = 429,
    ) {
        $message = $message ?: __('laravel-exceptions::exceptions.system.http.too_many_requests', ['resource' => $resource]);
        $userMessage = $userMessage ?: __('laravel-exceptions::exceptions.user.http.too_many_requests', ['resource' => $resource]);

        parent::__construct($resource, $message, $code, $previous, $context, $userMessage, $statusCode);
    }
}
