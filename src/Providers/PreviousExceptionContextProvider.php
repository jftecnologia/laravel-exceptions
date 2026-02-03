<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Providers;

use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;
use Throwable;

class PreviousExceptionContextProvider implements ExceptionContext
{
    public function getContext(?AppException $exception = null): array
    {
        if ($exception === null || ! ($exception->getPrevious() instanceof Throwable)) {
            return [];
        }

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

    public function shouldRun(?AppException $exception = null): bool
    {
        return $exception !== null && $exception->getPrevious() instanceof Throwable;
    }
}
