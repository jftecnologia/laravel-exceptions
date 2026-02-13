# Laravel Exceptions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jftecnologia/laravel-exceptions.svg?style=flat-square)](https://packagist.org/packages/jftecnologia/laravel-exceptions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jftecnologia/laravel-exceptions/tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/jftecnologia/laravel-exceptions/actions?query=workflow%3Atests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jftecnologia/laravel-exceptions/fix-php-code-style.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/jftecnologia/laravel-exceptions/actions?query=workflow%3A"fix-php-code-style-issues"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/jftecnologia/laravel-exceptions.svg?style=flat-square)](https://packagist.org/packages/jftecnologia/laravel-exceptions)

Advanced exception management system for Laravel with enriched context, multiple logging channels, and customized user-facing messages.

## Features

- **Custom exceptions** with separate messages for developers and end-users
- **Enriched context** automatically collected (user, host, environment, stack trace)
- **Multiple channels** for storage (database, etc.)
- **Automatic conversion** of Symfony HTTP exceptions
- **Database logging** with complete schema
- **Internationalization** support (en, es, pt_BR)
- **Artisan commands** for quick setup and exception generation

## Installation

```bash
composer require jftecnologia/laravel-exceptions
```

### Quick Install (Recommended)

Run the interactive installation command:

```bash
php artisan laravel-exceptions:install
```

This will guide you through publishing assets, migrations, configuration, and running migrations.

**After installation**, you must register the exception handler in your `bootstrap/app.php` file:

```php
use JuniorFontenele\LaravelExceptions\Facades\LaravelException;

return Application::configure(basePath: dirname(__DIR__))
    // ... other configurations
    ->withExceptions(function (Exceptions $exceptions) {
        // ... other exception handlers
        
        // Register Laravel Exceptions handler (must be the last handler)
        LaravelException::handles($exceptions);
    })
    ->create();
```

**Note:** The `LaravelException::handles()` call must be placed as the **last handler** in the `withExceptions` method to ensure it properly catches all exceptions.

### Manual Installation

**Important:** You must publish the package assets and migrations before using it:

```bash
php artisan vendor:publish --tag="laravel-exceptions-assets"
```

```bash
php artisan vendor:publish --tag="laravel-exceptions-migrations"
php artisan migrate
```

Optionally, publish configuration (if you want to customize it):

```bash
php artisan vendor:publish --tag="laravel-exceptions-config"
```

Optionally, publish views (if you want to customize them):

```bash
php artisan vendor:publish --tag="laravel-exceptions-views"
```

**After publishing**, you must register the exception handler in your `bootstrap/app.php` file:

```php
use JuniorFontenele\LaravelExceptions\Facades\LaravelException;

return Application::configure(basePath: dirname(__DIR__))
    // ... other configurations
    ->withExceptions(function (Exceptions $exceptions) {
        // ... other exception handlers
        
        // Register Laravel Exceptions handler (must be the last handler)
        LaravelException::handles($exceptions);
    })
    ->create();
```

**Note:** The `LaravelException::handles()` call must be placed as the **last handler** in the `withExceptions` method to ensure it properly catches all exceptions.

## Usage

### Creating Custom Exceptions

Use the artisan command to generate exception classes:

```bash
php artisan make:app-exception PaymentFailedException
```

This creates a new exception class in `app/Exceptions/PaymentFailedException.php`.

### Throwing Exceptions

```php
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;

// Basic exception
throw new AppException(
    message: 'Internal system error',
    userMessage: 'An error occurred. Please try again.',
    statusCode: 500
);

// With additional context
throw new AppException(
    message: 'Payment processing failed',
    userMessage: 'Unable to process payment.',
    statusCode: 422,
    context: [
        'payment_id' => $paymentId,
        'amount' => $amount,
    ]
);
```

### HTTP Exceptions

```php
use JuniorFontenele\LaravelExceptions\Exceptions\Http\NotFoundHttpException;
use JuniorFontenele\LaravelExceptions\Exceptions\Http\UnauthorizedHttpException;

throw new NotFoundHttpException('Resource not found');
throw new UnauthorizedHttpException('Access denied');
```

Available classes: `BadRequestHttpException`, `UnauthorizedHttpException`, `AccessDeniedHttpException`, `NotFoundHttpException`, `MethodNotAllowedHttpException`, `SessionExpiredHttpException`, `UnprocessableEntityHttpException`, `TooManyRequestsHttpException`, `InternalServerErrorHttpException`, `ServiceUnavailableHttpException`, `GatewayTimeoutHttpException`.

### Cleaning Old Exception Records

Use the clean command to remove old exception records from the database:

```bash
# Clean records using the default retention period (configured in config file)
php artisan laravel-exceptions:clean

# Clean records older than a specific number of days
php artisan laravel-exceptions:clean --days=90

# Force execution in production without confirmation
php artisan laravel-exceptions:clean --force
```

The retention period defaults to 365 days but can be configured via the `delete_records_older_than_days` setting.

**Recommended:** Schedule this command to run daily by adding it to your `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('laravel-exceptions:clean --force')
    ->daily()
    ->onOneServer();
```

## Configuration

Main options in `config/laravel-exceptions.php`:

```php
return [
    // Custom error view
    'view' => 'laravel-exceptions::error',
    
    // Convert unhandled exceptions to AppException
    'convert_exceptions' => env('LARAVEL_EXCEPTIONS_CONVERT_EXCEPTIONS', true),
    
    // Render custom exceptions even in debug mode
    'render_in_debug' => env('LARAVEL_EXCEPTIONS_RENDER_IN_DEBUG', false),
    
    // Days to keep exception records (used by clean command)
    'delete_records_older_than_days' => 365,
    
    // Automatic context providers
    'context_providers' => [
        AppExceptionContextProvider::class,
        AppContextProvider::class,
        HostContextProvider::class,
        UserContextProvider::class,
        ExceptionContextProvider::class,
        PreviousExceptionContextProvider::class,
    ],
    
    // Storage channels
    'channels' => [
        'database' => Database::class,
    ],
    
    // Channel-specific settings
    'channels_settings' => [
        'database' => [
            'table_name' => 'exceptions_log',
            'model' => Exception::class,
            'user_model' => config('auth.providers.users.model'),
            'user_model_table' => 'users',
        ],
    ],
    
    // Ignored exceptions (won't be logged or converted)
    'ignored_exceptions' => [
        AuthenticationException::class,
        ValidationException::class,
    ],
    
    // HTTP exception mappings
    'http_exceptions' => [
        400 => BadRequestHttpException::class,
        401 => UnauthorizedHttpException::class,
        403 => AccessDeniedHttpException::class,
        404 => NotFoundHttpException::class,
        // ... more status codes
    ],
];
```

### Configuration Options

| Option | Default | Description |
|--------|---------|-------------|
| `view` | `laravel-exceptions::error` | Blade view used for displaying exceptions |
| `convert_exceptions` | `true` | Convert unhandled exceptions to AppException automatically |
| `render_in_debug` | `false` | Render custom exception views even when `APP_DEBUG=true` |
| `delete_records_older_than_days` | `365` | Number of days to retain exception records (used by clean command) |
| `context_providers` | Array | Classes that provide additional context for exceptions |
| `channels` | Array | Storage channels for logging exceptions |
| `channels_settings` | Array | Channel-specific configuration options |
| `ignored_exceptions` | Array | Exception classes that should not be logged or converted |
| `http_exceptions` | Array | Mapping of HTTP status codes to exception classes |

## Automatic Context

The package automatically collects:

- **Exception**: class, message, file, line, code, stack trace
- **Application**: environment, debug mode
- **Host**: hostname, IP address
- **User**: authenticated user ID
- **Previous exception**: complete information if exists

## Database Storage

Exceptions are automatically saved to the `exceptions_log` table with all relevant fields, making analysis and debugging easier.

## Testing

```bash
composer test
```

## Credits

- [Junior Fontenele](https://github.com/juniorfontenele)

## License

MIT License. See [LICENSE.md](LICENSE.md) for details.
