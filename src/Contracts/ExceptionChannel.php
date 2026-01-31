<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Contracts;

interface ExceptionChannel
{
    public function send(array $context): void;
}
