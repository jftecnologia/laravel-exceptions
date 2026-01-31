<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Exceptions;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class AppException extends Exception
{
    protected string $errorId;

    protected int $statusCode = 500;

    protected string $userMessage;

    protected array $customContext = [];

    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $userMessage = '',
        int $statusCode = 500,
    ) {
        $this->errorId = Str::uuid()->toString();
        $this->customContext = $context;
        $this->userMessage = $userMessage ?: __('laravel-exceptions::exceptions.user.app');
        $this->statusCode = $statusCode;

        parent::__construct($message, $code, $previous);
    }

    public function withContext(array $context): static
    {
        $this->customContext = array_merge($this->customContext, $context);

        return $this;
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

    public function context(): array
    {
        return $this->buildContext();
    }

    protected function buildContext(): array
    {
        $user = Auth::user()?->only(['id', 'name', 'email']);

        return [
            'request' => [
                'method' => request()->method(),
                'uri' => request()->getRequestUri(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'full_url' => request()->fullUrl(),
            ],
            'status_code' => $this->statusCode,
            'error_id' => $this->errorId,
            'correlation_id' => session()->get('correlation_id'),
            'request_id' => session()->get('request_id'),
            'user' => [
                'id' => $user['id'] ?? null,
                'name' => $user['name'] ?? null,
                'email' => $user['email'] ?? null,
            ],
            'actual_exception' => [
                'class' => get_class($this),
                'message' => $this->getMessage(),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'code' => $this->getCode(),
            ],
            'previous_exception' => $this->getPrevious() instanceof Throwable ? [
                'class' => get_class($this->getPrevious()),
                'message' => $this->getPrevious()->getMessage(),
                'file' => $this->getPrevious()->getFile(),
                'line' => $this->getPrevious()->getLine(),
                'code' => $this->getPrevious()->getCode(),
            ] : null,
        ];
    }
}
