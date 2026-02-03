<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Providers;

use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use Throwable;

class ExceptionContextProvider implements ExceptionContext
{
    public function getContext(Throwable $exception): array
    {
        $context = [
            'exception_detail' => [
                'class' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
                'stack_trace' => $exception->getTraceAsString(),
            ],
        ];

        return $context;
    }

    public function shouldRun(Throwable $exception): bool
    {
        return true;
    }
}
