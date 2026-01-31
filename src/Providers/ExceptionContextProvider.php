<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Providers;

use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;

class ExceptionContextProvider implements ExceptionContext
{
    public function getContext(?AppException $exception = null): array
    {
        return $exception === null ? [] : [
            'error_id' => $exception->getErrorId(),
            'exception_class' => get_class($exception),
            'message' => $exception->getMessage(),
            'user_message' => $exception->getUserMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $exception->getCode(),
            'status_code' => $exception->getStatusCode(),
            'is_retryable' => $exception->isRetryable(),
            'context' => $exception->context(),
            'stack_trace' => $exception->getTraceAsString(),
        ];
    }

    public function shouldRun(?AppException $exception = null): bool
    {
        return $exception instanceof AppException;
    }
}
