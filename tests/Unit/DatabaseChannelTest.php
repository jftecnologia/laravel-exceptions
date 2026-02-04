<?php

declare(strict_types = 1);

use Illuminate\Database\Eloquent\Model;
use JuniorFontenele\LaravelExceptions\Channels\Database;
use Psr\Log\LoggerInterface;

uses(Tests\TestCase::class);

beforeEach(function () {
    $this->logger = Mockery::mock(LoggerInterface::class);
    $this->model = Mockery::mock(Model::class);
    $this->channel = new Database($this->model, $this->logger);
});

afterEach(function () {
    Mockery::close();
});

test('Database channel sends exception to database', function () {
    $exception = new Exception('Test error');
    $context = [
        'exception_detail' => [
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $exception->getCode(),
            'stack_trace' => $exception->getTraceAsString(),
        ],
        'error' => [
            'id' => 'error-123',
            'user_message' => 'Something went wrong',
            'status_code' => 500,
            'is_retryable' => false,
        ],
        'app' => [
            'env' => 'testing',
            'debug' => true,
        ],
        'host' => [
            'name' => 'localhost',
            'ip' => '127.0.0.1',
        ],
        'user' => [
            'id' => 1,
        ],
    ];

    $this->model->shouldReceive('create')
        ->once()
        ->with(Mockery::on(function ($data) {
            return $data['exception_class'] === Exception::class
                && $data['message'] === 'Test error'
                && $data['status_code'] === 500
                && $data['error_id'] === 'error-123';
        }))
        ->andReturn($this->model);

    $this->channel->send($exception, $context);
});

test('Database channel handles database errors gracefully', function () {
    $exception = new Exception('Test error');
    $context = [
        'exception_detail' => [
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
        ],
    ];

    $dbException = new Exception('Database connection failed');

    $this->model->shouldReceive('create')
        ->once()
        ->andThrow($dbException);

    $this->logger->shouldReceive('error')
        ->once()
        ->with('Failed to save exception to database', [
            'error' => 'Database connection failed',
        ]);

    // Should not throw exception
    $this->channel->send($exception, $context);

    expect(true)->toBeTrue();
});
