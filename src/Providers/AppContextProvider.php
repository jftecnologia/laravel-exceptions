<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Providers;

use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;

class AppContextProvider implements ExceptionContext
{
    public function getContext(?AppException $exception = null): array
    {
        return $exception === null ? [] : [
            'app_env' => app()->environment(),
            'app_debug' => app()->hasDebugModeEnabled(),
        ];
    }

    public function shouldRun(?AppException $exception = null): bool
    {
        return true;
    }
}
