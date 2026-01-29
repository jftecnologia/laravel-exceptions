<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Contracts;

interface ExceptionContext
{
    public function all(): array;

    public function set(string $key, $value): void;

    public function get(string $key, $default = null);

    public function has(string $key): bool;
}
