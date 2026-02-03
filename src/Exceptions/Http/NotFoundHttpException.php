<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Exceptions\Http;

use JuniorFontenele\LaravelExceptions\Exceptions\HttpException;
use Throwable;

class NotFoundHttpException extends HttpException
{
    public function __construct(
        string $resource,
        string $message = '',
        int|string $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $userMessage = '',
        int $statusCode = 404,
    ) {
        $message = $message ?: __('laravel-exceptions::exceptions.system.http.not_found', ['resource' => $resource]);
        $userMessage = $userMessage ?: __('laravel-exceptions::exceptions.user.http.not_found', ['resource' => $resource]);

        parent::__construct($resource, $message, $code, $previous, $context, $userMessage, $statusCode);
    }
}
