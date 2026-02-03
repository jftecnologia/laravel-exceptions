<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Contracts;

use Throwable;

interface ExceptionContext
{
    public function getContext(Throwable $exception): array;

    public function shouldRun(Throwable $exception): bool;
}
