<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionModel;

class Exception extends Model implements ExceptionModel
{
    protected static function booted(): void
    {
        static::unguard();
    }

    public function getTable(): string
    {
        return config('laravel-exceptions.table_name', parent::getTable());
    }

    protected function casts(): array
    {
        return [
            'is_retryable' => 'boolean',
            'context' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('laravel-exceptions.user_model'));
    }
}
