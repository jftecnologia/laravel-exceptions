<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Contracts;

use Throwable;

interface ExceptionChannel
{
    public function send(Throwable $exception, ExceptionContext $context): void;
}
