<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Exceptions;

use Throwable;

class HttpException extends AppException
{
    protected string $resource;

    public function __construct(
        string $resource,
        string $message = '',
        int|string $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $userMessage = '',
        int $statusCode = 500,
    ) {
        $this->resource = $resource;
        $message = $message ?: __('laravel-exceptions::exceptions.system.http.internal_server_error', ['resource' => $resource]);
        $userMessage = $userMessage ?: __('laravel-exceptions::exceptions.user.http.internal_server_error', ['resource' => $resource]);

        parent::__construct($message, $code, $previous, $context, $userMessage, $statusCode);
    }

    public function context(): array
    {
        return array_merge(parent::context(), [
            'resource' => $this->resource,
        ]);
    }
}
