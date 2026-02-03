<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Response;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionChannel;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;
use JuniorFontenele\LaravelExceptions\Exceptions\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;
use Throwable;

class ExceptionManager
{
    /** @var ExceptionContext[] */
    protected array $contextProviders = [];

    /** @var ExceptionChannel[] */
    protected array $channels = [];

    protected array $context = [];

    protected bool $hasBuiltContext = false;

    protected array $ignoredExceptions;

    protected string $errorView;

    protected bool $shouldConvertExceptions;

    protected bool $shouldRenderInDebug;

    protected array $httpExceptions;

    public function __construct(
        protected array $config = [],
    ) {
        $this->ignoredExceptions = $config['ignored_exceptions'] ?? [];
        $this->errorView = $config['view'] ?? 'laravel-exceptions::error';
        $this->shouldConvertExceptions = $config['convert_exceptions'] ?? true;
        $this->shouldRenderInDebug = $config['render_in_debug'] ?? false;
        $this->httpExceptions = $config['http_exceptions'] ?? [];
    }

    public function addContextProvider(ExceptionContext $provider): static
    {
        $this->contextProviders[] = $provider;

        return $this;
    }

    public function addChannel(ExceptionChannel $channel): static
    {
        $this->channels[] = $channel;

        return $this;
    }

    public function buildContext(Throwable $exception): static
    {
        $this->context = [];

        foreach ($this->contextProviders as $provider) {
            if ($provider->shouldRun($exception)) {
                $this->context = array_merge($this->context, $provider->getContext($exception));
            }
        }

        $this->hasBuiltContext = true;

        return $this;
    }

    public function context(): array
    {
        return $this->context;
    }

    protected function sendToChannels(Throwable $exception, array $context): void
    {
        foreach ($this->channels as $channel) {
            $channel->send($exception, $context);
        }
    }

    public function handles(Exceptions $exceptions): void
    {
        $exceptions->render(function (Throwable $e): bool|Response {
            if (! $this->shouldHandleException($e)) {
                return false;
            }

            if ($e instanceof AppException) {
                if (! $this->hasBuiltContext) {
                    $this->buildContext($e);
                }

                return response()->view($this->errorView, [
                    'code' => $e->getErrorId(),
                    'message' => $e->getUserMessage(),
                ], $e->getStatusCode());
            }

            if ($this->shouldConvertExceptions) {
                throw $this->convertException($e);
            }

            return false;
        });

        $exceptions->report(function (Throwable $e) {
            if (! $this->shouldHandleException($e)) {
                return false;
            }

            if ($e instanceof AppException) {
                if (! $this->hasBuiltContext) {
                    $this->buildContext($e);
                }

                $this->sendToChannels($e, $this->context());

                return true;
            }

            if ($this->shouldConvertExceptions) {
                throw $this->convertException($e);
            }

            return false;
        });

        $exceptions->context(function (Throwable $e) {
            if (! $this->hasBuiltContext) {
                $this->buildContext($e);
            }

            return $this->context;
        });
    }

    protected function shouldHandleException(Throwable $exception): bool
    {
        if (! $this->shouldRenderInDebug && app()->hasDebugModeEnabled()) {
            return false;
        }

        foreach ($this->ignoredExceptions as $ignoredException) {
            if ($exception instanceof $ignoredException) {
                return false;
            }
        }

        return true;
    }

    protected function convertException(Throwable $exception): AppException
    {
        if ($exception instanceof AppException) {
            return $exception;
        }

        if ($exception instanceof SymfonyHttpException) {
            if (! array_key_exists((int) $exception->getStatusCode(), $this->httpExceptions)) {
                return new HttpException($exception->getMessage(), $exception->getCode(), $exception);
            }

            $customExceptionClass = $this->httpExceptions[(int) $exception->getStatusCode()];

            if (! class_exists($customExceptionClass) || ! is_subclass_of($customExceptionClass, HttpException::class)) {
                return new HttpException($exception->getMessage(), $exception->getCode(), $exception);
            }

            return new $customExceptionClass(
                message: $exception->getMessage(),
                code: $exception->getCode(),
                previous: $exception,
            );
        }

        return new AppException($exception->getMessage(), $exception->getCode(), $exception);
    }
}
