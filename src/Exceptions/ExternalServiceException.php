<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Exceptions;

use Throwable;

class ExternalServiceException extends AppException
{
    protected string $service;

    public function __construct(
        string $service,
        string $message = '',
        int|string $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $userMessage = '',
        int $statusCode = 503,
    ) {
        $this->service = $service;
        $message = $message ?: __('laravel-exceptions::exceptions.system.external_service', ['service' => $service]);
        $userMessage = $userMessage ?: __('laravel-exceptions::exceptions.user.external_service', ['service' => $service]);

        parent::__construct($message, $code, $previous, $context, $userMessage, $statusCode);
    }

    public function context(): array
    {
        return array_merge(parent::context(), [
            'service' => $this->service,
        ]);
    }
}
