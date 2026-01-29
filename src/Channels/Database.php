<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Channels;

use JuniorFontenele\LaravelExceptions\Contracts\ExceptionChannel;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use JuniorFontenele\LaravelExceptions\Models\ExceptionModel;
use Throwable;

class Database implements ExceptionChannel
{
    public function __construct(protected ExceptionModel $exceptionModel)
    {
        //
    }

    public function send(Throwable $exception, ExceptionContext $context): void
    {
        try {
            $this->exceptionModel->create([
                'error_id' => $context['errorId'],
                'exception_class' => get_class($exception),
                'message' => $exception->getMessage(),
                'user_message' => $context['userMessage'],
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $context['code'],
                'status_code' => $context['statusCode'],
                'is_retryable' => $context['isRetryable'],

                // Context data
                'app_version' => $context['app']['version'] ?? null,
                'app_env' => $context['app']['env'] ?? null,
                'correlation_id' => $context['correlation_id'] ?? null,
                'request_id' => $context['request_id'] ?? null,
                'user_id' => $context['user']['id'] ?? null,

                // JSON fields
                'context' => $context,
                'stack_trace' => $this->getTraceAsString(),
                'previous_exception' => $this->getPrevious() ? [
                    'class' => get_class($this->getPrevious()),
                    'message' => $this->getPrevious()->getMessage(),
                ] : null,
            ]);
        } catch (Throwable $e) {
            // Falha silenciosa para não quebrar a aplicação
            logger()->error('Failed to save exception to database', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
