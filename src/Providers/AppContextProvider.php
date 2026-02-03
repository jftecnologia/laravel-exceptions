<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Providers;

use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use Throwable;

class AppContextProvider implements ExceptionContext
{
    public function getContext(Throwable $exception): array
    {
        return [
            'app' => [
                'env' => app()->environment(),
                'debug' => app()->hasDebugModeEnabled(),
            ],
        ];
    }

    public function shouldRun(Throwable $exception): bool
    {
        return true;
    }
}
