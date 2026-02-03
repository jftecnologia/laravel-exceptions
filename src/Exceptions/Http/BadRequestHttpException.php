<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Exceptions\Http;

use JuniorFontenele\LaravelExceptions\Exceptions\HttpException;
use Throwable;

class BadRequestHttpException extends HttpException
{
    public function __construct(
        string $resource,
        string $message = '',
        int|string $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $userMessage = '',
        int $statusCode = 400,
    ) {
        $message = $message ?: __('laravel-exceptions::exceptions.system.http.bad_request', ['resource' => $resource]);
        $userMessage = $userMessage ?: __('laravel-exceptions::exceptions.user.http.bad_request', ['resource' => $resource]);

        parent::__construct($resource, $message, $code, $previous, $context, $userMessage, $statusCode);
    }
}
