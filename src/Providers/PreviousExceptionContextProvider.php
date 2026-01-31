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
        return $exception === null ? [] : [
            'previous_exception' => [
                'class' => get_class($exception->getPrevious()),
                'message' => $exception->getPrevious()->getMessage(),
            ],
        ];
    }

    public function shouldRun(?AppException $exception = null): bool
    {
        return $exception->getPrevious() instanceof Throwable;
    }
}
