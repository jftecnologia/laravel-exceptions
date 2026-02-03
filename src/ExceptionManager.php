<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Response;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionChannel;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionContext;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;
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

    public function __construct(
        protected array $ignoredExceptions = [],
        protected string $errorView = 'laravel-exceptions::error',
        protected bool $shouldConvertExceptions = true,
        protected bool $shouldRenderInDebug = false,
    ) {
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

        // if ($exception instanceof SymfonyHttpException) {
        //     return match ($exception->getStatusCode()) {
        //         404 => new NotFoundHttpException(previous: $exception->getPrevious()),
        //         401 => new UnauthorizedHttpException(previous: $exception->getPrevious()),
        //         403 => new ForbiddenHttpException(previous: $exception->getPrevious()),
        //         500 => new InternalServerErrorHttpException(previous: $exception->getPrevious()),
        //         503 => new ServiceUnavailableHttpException(previous: $exception->getPrevious()),
        //         default => new AppException(previous: $exception->getPrevious()),
        //     };
        // }

        return new AppException($exception->getMessage(), $exception->getCode(), $exception);
    }
}
