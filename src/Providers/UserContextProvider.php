<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Providers;

use Illuminate\Support\Facades\Auth;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use Throwable;

class UserContextProvider implements ExceptionContext
{
    public function getContext(Throwable $exception): array
    {
        return [
            'user' => [
                'id' => Auth::id(),
                'name' => Auth::user()?->name,
                'email' => Auth::user()?->email,
            ],
        ];
    }

    public function shouldRun(Throwable $exception): bool
    {
        return Auth::check();
    }
}
