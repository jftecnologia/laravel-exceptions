<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Providers;

use Illuminate\Support\Facades\Auth;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;

class UserContextProvider implements ExceptionContext
{
    public function getContext(?AppException $exception = null): array
    {
        return $exception === null ? [] : [
            'user_id' => Auth::id(),
        ];
    }

    public function shouldRun(?AppException $exception = null): bool
    {
        return Auth::check();
    }
}
