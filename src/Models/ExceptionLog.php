<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExceptionLog extends ExceptionModel
{
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
