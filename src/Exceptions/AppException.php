<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Exceptions;

use Exception;
use Illuminate\Support\Str;
use Throwable;

class AppException extends Exception
{
    protected string $errorId;

    protected int $statusCode;

    protected string $userMessage;

    protected array $customContext;

    public function __construct(
        string $message = '',
        int|string $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $userMessage = '',
        int $statusCode = 500,
    ) {
        $this->errorId = Str::uuid()->toString();
        $this->customContext = $context;
        $this->message = $message ?: __('laravel-exceptions::exceptions.system.app');
        $this->userMessage = $userMessage ?: __('laravel-exceptions::exceptions.user.app');
        $this->statusCode = $statusCode;

        parent::__construct($message, (int) $code, $previous);
    }

    public function withContext(array $context): static
    {
        $this->customContext = array_merge($this->customContext, $context);

        return $this;
    }

    public function context(): array
    {
        return $this->customContext;
    }

    public function getErrorId(): string
    {
        return $this->errorId;
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function isRetryable(): bool
    {
        return false;
    }
}
