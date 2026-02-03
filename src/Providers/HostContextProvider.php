<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Providers;

use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use Throwable;

class HostContextProvider implements ExceptionContext
{
    public function getContext(Throwable $exception): array
    {
        return [
            'host' => [
                'name' => gethostname(),
                'ip' => gethostbyname(gethostname()),
            ],
        ];
    }

    public function shouldRun(Throwable $exception): bool
    {
        return true;
    }
}
