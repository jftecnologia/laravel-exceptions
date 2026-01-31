<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Channels;

use Illuminate\Support\Arr;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionChannel;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionModel;
use Throwable;

class Database implements ExceptionChannel
{
    public function __construct(protected ExceptionModel $exceptionModel)
    {
        //
    }

    public function send(array $context): void
    {
        try {
            $this->exceptionModel->fill([
                'error_id' => Arr::get($context, 'errorId'),
                'exception_class' => Arr::get($context, 'exceptionClass'),
                'message' => Arr::get($context, 'message'),
                'user_message' => Arr::get($context, 'userMessage'),
                'file' => Arr::get($context, 'file'),
                'line' => Arr::get($context, 'line'),
                'code' => Arr::get($context, 'code'),
                'status_code' => Arr::get($context, 'statusCode'),
                'is_retryable' => Arr::get($context, 'isRetryable'),

                // Context data
                'app_version' => Arr::get($context, 'app.version'),
                'app_env' => Arr::get($context, 'app.env'),
                'correlation_id' => Arr::get($context, 'correlation_id'),
                'request_id' => Arr::get($context, 'request_id'),
                'user_id' => Arr::get($context, 'user.id'),
                // JSON fields
                'context' => $context,
                'stack_trace' => $this->getTraceAsString(),
                'previous_exception' => $this->getPrevious() ? [
                    'class' => get_class($this->getPrevious()),
                    'message' => $this->getPrevious()->getMessage(),
                ] : null,
            ]);

            $this->exceptionModel->save();
        } catch (Throwable $e) {
            // Falha silenciosa para não quebrar a aplicação
            logger()->error('Failed to save exception to database', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
