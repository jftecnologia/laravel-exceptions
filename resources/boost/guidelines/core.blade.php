## Laravel Exceptions

This package provides advanced exception management for Laravel with enriched context, automatic logging to multiple channels (including database), and customized user-facing messages.

### Installation

After installing the package, users must register the exception handler in `bootstrap/app.php`:

@verbatim
<code-snippet name="Register Exception Handler" lang="php">
use JuniorFontenele\LaravelExceptions\Facades\LaravelException;

return Application::configure(basePath: dirname(__DIR__))
    ->withExceptions(function (Exceptions $exceptions) {
        // Register Laravel Exceptions handler (must be the last handler)
        LaravelException::handles($exceptions);
    })
    ->create();
</code-snippet>
@endverbatim

**Important:** The `LaravelException::handles()` call must be placed as the **last handler** in the `withExceptions` method.

### Features

- **Custom Exceptions**: Generate custom exception classes with separate messages for developers and end-users.
- **HTTP Exceptions**: Pre-built exception classes for common HTTP status codes (400, 401, 403, 404, 422, 429, 500, 503, etc.).
- **Automatic Context**: Collects environment, user, host, stack trace, and previous exception data automatically.
- **Database Logging**: Stores exceptions in the `exceptions_log` table with full context for debugging.
- **Configurable Channels**: Extensible channel system for logging to multiple destinations.

### Creating Custom Exceptions

Generate new exception classes using the artisan command:

@verbatim
<code-snippet name="Generate Custom Exception" lang="bash">
php artisan make:app-exception PaymentFailedException
</code-snippet>
@endverbatim

This creates a new exception class in `app/Exceptions/PaymentFailedException.php` that extends `AppException`.

### Throwing Exceptions

Basic exception with user-friendly message:

@verbatim
<code-snippet name="Throw Basic Exception" lang="php">
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;

throw new AppException(
    message: 'Internal system error',
    userMessage: 'An error occurred. Please try again.',
    statusCode: 500
);
</code-snippet>
@endverbatim

Exception with additional context for debugging:

@verbatim
<code-snippet name="Throw Exception with Context" lang="php">
throw new AppException(
    message: 'Payment processing failed',
    userMessage: 'Unable to process payment.',
    statusCode: 422,
    context: [
        'payment_id' => $paymentId,
        'amount' => $amount,
        'gateway' => $gateway,
    ]
);
</code-snippet>
@endverbatim

### Using HTTP Exceptions

Use pre-built HTTP exception classes for common status codes:

@verbatim
<code-snippet name="HTTP Exceptions" lang="php">
use JuniorFontenele\LaravelExceptions\Exceptions\Http\NotFoundHttpException;
use JuniorFontenele\LaravelExceptions\Exceptions\Http\UnauthorizedHttpException;
use JuniorFontenele\LaravelExceptions\Exceptions\Http\UnprocessableEntityHttpException;

// 404 Not Found
throw new NotFoundHttpException('Resource not found');

// 401 Unauthorized
throw new UnauthorizedHttpException('Access denied');

// 422 Unprocessable Entity
throw new UnprocessableEntityHttpException(
    message: 'Invalid data provided',
    userMessage: 'Please check your input and try again.',
    context: ['errors' => $validator->errors()]
);
</code-snippet>
@endverbatim

Available HTTP exception classes: `BadRequestHttpException` (400), `UnauthorizedHttpException` (401), `AccessDeniedHttpException` (403), `NotFoundHttpException` (404), `MethodNotAllowedHttpException` (405), `SessionExpiredHttpException` (419), `UnprocessableEntityHttpException` (422), `TooManyRequestsHttpException` (429), `InternalServerErrorHttpException` (500), `ServiceUnavailableHttpException` (503), `GatewayTimeoutHttpException` (504).

### Configuration

Publish and customize the configuration file:

@verbatim
<code-snippet name="Publish Configuration" lang="bash">
php artisan vendor:publish --tag="laravel-exceptions-config"
</code-snippet>
@endverbatim

Key configuration options in `config/laravel-exceptions.php`:

- `convert_exceptions`: Automatically convert unhandled exceptions to `AppException` (default: true)
- `render_in_debug`: Render custom exception views even when `APP_DEBUG=true` (default: false)
- `ignored_exceptions`: Array of exception classes that should not be logged or converted
- `context_providers`: Classes that collect additional context for exceptions
- `channels`: Storage channels for logging exceptions (e.g., database)

### Cleaning Old Exception Records

Clean old exception records from the database using the artisan command:

@verbatim
<code-snippet name="Clean Old Exceptions" lang="bash">
# Clean using default retention period (365 days)
php artisan laravel-exceptions:clean

# Clean records older than 90 days
php artisan laravel-exceptions:clean --days=90

# Schedule daily cleanup in routes/console.php
Schedule::command('laravel-exceptions:clean --force')
    ->daily()
    ->onOneServer();
</code-snippet>
@endverbatim

### Best Practices

- Always provide both `message` (for developers) and `userMessage` (for end-users) when throwing exceptions.
- Include relevant context data to aid debugging (e.g., IDs, parameters, state).
- Use appropriate HTTP status codes for HTTP exceptions.
- Schedule the `laravel-exceptions:clean` command to run daily in production.
- Place `LaravelException::handles()` as the **last handler** in `withExceptions()` to ensure it catches all exceptions.
- Use the pre-built HTTP exception classes instead of generic exceptions for better semantics.
