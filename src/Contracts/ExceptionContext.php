<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Contracts;

use JuniorFontenele\LaravelExceptions\Exceptions\AppException;

interface ExceptionContext
{
    public function getContext(?AppException $exception = null): array;

    public function shouldRun(?AppException $exception = null): bool;
}
