<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Exceptions\Http;

use JuniorFontenele\LaravelExceptions\Exceptions\HttpException;
use Throwable;

class UnprocessableEntityHttpException extends HttpException
{
    public function __construct(
        string $resource,
        string $message = '',
        int|string $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $userMessage = '',
        int $statusCode = 422,
    ) {
        $message = $message ?: __('laravel-exceptions::exceptions.system.http.unprocessable_entity', ['resource' => $resource]);
        $userMessage = $userMessage ?: __('laravel-exceptions::exceptions.user.http.unprocessable_entity', ['resource' => $resource]);

        parent::__construct($resource, $message, $code, $previous, $context, $userMessage, $statusCode);
    }
}
