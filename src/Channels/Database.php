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

    public function send(array $context): void
    {
        try {
            $this->exceptionModel->create([
                'exception_class' => Arr::get($context, 'exception_class'),
                'message' => Arr::get($context, 'message'),
                'user_message' => Arr::get($context, 'user_message'),
                'file' => Arr::get($context, 'file'),
                'line' => Arr::get($context, 'line'),
                'code' => Arr::get($context, 'code'),
                'status_code' => Arr::get($context, 'status_code'),
                'error_id' => Arr::get($context, 'error_id'),
                'app_env' => Arr::get($context, 'app_env'),
                'host_name' => Arr::get($context, 'host_name'),
                'host_ip' => Arr::get($context, 'host_ip'),
                'user_id' => Arr::get($context, 'user_id'),
                'is_retryable' => Arr::get($context, 'is_retryable'),
                'stack_trace' => Arr::get($context, 'stack_trace'),
                'context' => Arr::get($context, 'context'),
                'previous_exception_class' => Arr::get($context, 'previous_exception_class'),
                'previous_message' => Arr::get($context, 'previous_message'),
                'previous_file' => Arr::get($context, 'previous_file'),
                'previous_line' => Arr::get($context, 'previous_line'),
                'previous_code' => Arr::get($context, 'previous_code'),
                'previous_stack_trace' => Arr::get($context, 'previous_stack_trace'),
            ]);
        } catch (Throwable $e) {
            // Falha silenciosa para não quebrar a aplicação
            $this->logger->error('Failed to save exception to database', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
