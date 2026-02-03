<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Providers;

use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use Throwable;

class PreviousExceptionContextProvider implements ExceptionContext
{
    public function getContext(Throwable $exception): array
    {
        return [
            'previous_exception' => [
                'class' => get_class($exception->getPrevious()),
                'message' => $exception->getPrevious()->getMessage(),
                'file' => $exception->getPrevious()->getFile(),
                'line' => $exception->getPrevious()->getLine(),
                'code' => $exception->getPrevious()->getCode(),
                'stack_trace' => $exception->getPrevious()->getTraceAsString(),
            ],
        ];
    }

    public function shouldRun(Throwable $exception): bool
    {
        return $exception->getPrevious() instanceof Throwable;
    }
}
