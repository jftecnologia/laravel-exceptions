<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Channels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionChannel;
use Psr\Log\LoggerInterface;
use Throwable;

class Database implements ExceptionChannel
{
    public function __construct(
        protected Model $exceptionModel,
        protected LoggerInterface $logger
    ) {
        //
    }

    public function send(Throwable $exception, array $context): void
    {
        try {
            $this->exceptionModel->create([
                'exception_class' => Arr::get($context, 'exception_detail.class'),
                'message' => Arr::get($context, 'exception_detail.message'),
                'user_message' => Arr::get($context, 'error.user_message'),
                'file' => Arr::get($context, 'exception_detail.file'),
                'line' => Arr::get($context, 'exception_detail.line'),
                'code' => Arr::get($context, 'exception_detail.code'),
                'status_code' => Arr::get($context, 'error.status_code'),
                'error_id' => Arr::get($context, 'error.id'),
                'app_env' => Arr::get($context, 'app.env'),
                'app_debug' => Arr::get($context, 'app.debug'),
                'host_name' => Arr::get($context, 'host.name'),
                'host_ip' => Arr::get($context, 'host.ip'),
                'user_id' => Arr::get($context, 'user.id'),
                'is_retryable' => Arr::get($context, 'error.is_retryable'),
                'stack_trace' => Arr::get($context, 'exception_detail.stack_trace'),
                'previous_exception_class' => Arr::get($context, 'previous_exception.class'),
                'previous_message' => Arr::get($context, 'previous_exception.message'),
                'previous_file' => Arr::get($context, 'previous_exception.file'),
                'previous_line' => Arr::get($context, 'previous_exception.line'),
                'previous_code' => Arr::get($context, 'previous_exception.code'),
                'previous_stack_trace' => Arr::get($context, 'previous_exception.stack_trace'),
            ]);
        } catch (Throwable $e) {
            // Silently fail to avoid breaking the application
            $this->logger->error('Failed to save exception to database', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
