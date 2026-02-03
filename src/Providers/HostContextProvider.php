<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Providers;

use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;

class HostContextProvider implements ExceptionContext
{
    public function getContext(?AppException $exception = null): array
    {
        return $exception === null ? [] : [
            'host' => gethostname(),
            'ip' => gethostbyname(gethostname()),
        ];
    }

    public function shouldRun(?AppException $exception = null): bool
    {
        return true;
    }
}
