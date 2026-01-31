<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Facades;

use Illuminate\Support\Facades\Facade;

class ExceptionHandler extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JuniorFontenele\LaravelExceptions\ExceptionManager::class;
    }
}
