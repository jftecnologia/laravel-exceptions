<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    public function buildContext(AppException $exception): static
    {
        $this->context = [];

        Log::debug('CHAMOU BUILD CONTEXT');

        foreach ($this->contextProviders as $provider) {
            if ($provider->shouldRun($exception)) {
                $this->context = array_merge($this->context, $provider->getContext($exception));
            }
        }

        $this->context = array_merge($this->context, $exception->context());

        $this->hasBuiltContext = true;

        return $this;
    }

    public function context(): array
    {
        return $this->context;
    }

    protected function sendToChannels(): void
    {
        foreach ($this->channels as $channel) {
            $channel->send($this->context);
        }
    }

    public function handles(Exceptions $exceptions): void
    {
        $exceptions->render(function (Throwable $e) {
            if (! $this->shouldConvertException($e)) {
                return false;
            }

            $convertedException = $this->convertException($e);

            if (! $this->hasBuiltContext) {
                $this->buildContext($convertedException);
            }

            Log::debug('CHAMOU RENDER');

            return response()->view($this->errorView, [
                'errorId' => $convertedException->getErrorId(),
                'userMessage' => $convertedException->getUserMessage(),
                'statusCode' => $convertedException->getStatusCode(),
            ], $convertedException->getStatusCode());
        });

        $exceptions->report(function (Throwable $e) {
            if (! $this->shouldConvertException($e)) {
                return false;
            }

            $convertedException = $this->convertException($e);

            if (! $this->hasBuiltContext) {
                $this->buildContext($convertedException);
            }

            Log::debug('CHAMOU REPORT');

            $this->sendToChannels();
            // throw $convertedException;
            // $this->logExceptionToDatabase($e);
        });

        $exceptions->context(function (Throwable $e) {
            if (! $this->shouldConvertException($e)) {
                return false;
            }

            $convertedException = $this->convertException($e);

            if (! $this->hasBuiltContext) {
                $this->buildContext($convertedException);
            }

            Log::debug('CHAMOU CONTEXT');

            return $convertedException->context();
        });
    }

    protected function shouldConvertException(Throwable $exception): bool
    {
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

    protected function logExceptionToDatabase(AppException $exception): bool
    {
        try {
            Models\Exception::create([
                'exception_class' => get_class($exception),
                'message' => $exception->getMessage(),
                'user_message' => $exception->getUserMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
                'status_code' => $exception->getStatusCode(),
                'error_id' => $exception->getErrorId(),
                'correlation_id' => session()->get('correlation_id'),
                'request_id' => session()->get('request_id'),
                'app_version' => config('app.version'),
                'app_commit' => config('app.commit'),
                'app_build_date' => config('app.build_date'),
                'app_role' => config('app.role'),
                'host_name' => gethostname(),
                'host_ip' => gethostbyname(gethostname()),
                'user_id' => Auth::id(),
                'is_retryable' => $exception->isRetryable(),
                'stack_trace' => $exception->getTraceAsString(),
                'context' => $exception->context(),
                'previous_exception_class' => $exception->getPrevious() instanceof Throwable ? get_class($exception->getPrevious()) : null,
                'previous_message' => $exception->getPrevious()?->getMessage(),
                'previous_file' => $exception->getPrevious()?->getFile(),
                'previous_line' => $exception->getPrevious()?->getLine(),
                'previous_code' => $exception->getPrevious()?->getCode(),
                'previous_stack_trace' => $exception->getPrevious()?->getTraceAsString(),
            ]);
        } catch (Throwable $e) {
            $this->logger->error('Failed to log exception to database', [
                'original_exception' => [
                    'class' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'code' => $exception->getCode(),
                ],
                'logging_exception' => [
                    'class' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                ],
            ]);
        }

        return false;
    }
}
