<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\Facades\Auth;
use JuniorFontenele\Exceptions\AppException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;
use Throwable;

class ExceptionHandler
{
    public function __construct(
        protected LoggerInterface $logger,
    ) {
    }

    public static function handles(Exceptions $exceptions): void
    {
        $exceptions->render(function (Throwable $e, ExceptionHandler $handler) {
            // Verifica se é HttpException personalizado e retorna a view de erro correspondente
            // Verifica se é Symfony HttpException e relança como HttpException personalizado
            // Verifica se é AppException e retorna nulo para continuar o fluxo normal
            // Converte outras exceções para AppException

            if (! $handler->shouldConvertException($e)) {
                return null;
            }

            $convertedException = $handler->convertException($e);

            return response()->view('laravel-exceptions::error', [
                'errorId' => $convertedException->getErrorId(),
                'userMessage' => $convertedException->getUserMessage(),
                'statusCode' => $convertedException->getStatusCode(),
            ], $convertedException->getStatusCode());

            throw $convertedException;
        });

        $exceptions->report(function (AppException $e, ExceptionHandler $handler): void {
            $handler->logExceptionToDatabase($e);
        });

        $exceptions->context(fn () => [
            'app_environment' => config('app.env'),
            'app_debug' => config('app.debug'),
        ]);
    }

    protected function shouldConvertException(Throwable $exception): bool
    {
        if ($exception instanceof AppException) {
            return false;
        }

        return true;
    }

    protected function convertException(Throwable $exception): AppException
    {
        if ($exception instanceof SymfonyHttpException) {
            return match ($exception->getStatusCode()) {
                404 => new NotFoundHttpException(previous: $exception->getPrevious()),
                401 => new UnauthorizedHttpException(previous: $exception->getPrevious()),
                403 => new ForbiddenHttpException(previous: $exception->getPrevious()),
                500 => new InternalServerErrorHttpException(previous: $exception->getPrevious()),
                503 => new ServiceUnavailableHttpException(previous: $exception->getPrevious()),
                default => new AppException(previous: $exception->getPrevious()),
            };
        }

        return new AppException($exception->getMessage(), $exception->getCode(), $exception);
    }

    protected function handleRenderableException(Throwable $exception): void
    {
        // Custom rendering logic can be implemented here
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
