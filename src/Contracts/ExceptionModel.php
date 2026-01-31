<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Contracts;

interface ExceptionModel
{
    public function fill(array $attributes);

    public function save();
}
