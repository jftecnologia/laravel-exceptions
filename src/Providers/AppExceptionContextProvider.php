<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Providers;

use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;
use Throwable;

class AppExceptionContextProvider implements ExceptionContext
{
    public function getContext(Throwable $exception): array
    {
        if (! $exception instanceof AppException) {
            return [];
        }

        return [
            'error' => [
                'id' => $exception->getErrorId(),
                'user_message' => $exception->getUserMessage(),
                'status_code' => $exception->getStatusCode(),
                'is_retryable' => $exception->isRetryable(),
            ],
        ];
    }

    public function shouldRun(Throwable $exception): bool
    {
        return $exception instanceof AppException;
    }
}
