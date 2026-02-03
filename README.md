# Laravel Exceptions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/juniorfontenele/laravel-exceptions.svg?style=flat-square)](https://packagist.org/packages/juniorfontenele/laravel-exceptions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/juniorfontenele/laravel-exceptions/tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/juniorfontenele/laravel-exceptions/actions?query=workflow%3Atests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/juniorfontenele/laravel-exceptions/fix-php-code-style.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/juniorfontenele/laravel-exceptions/actions?query=workflow%3A"fix-php-code-style-issues"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/juniorfontenele/laravel-exceptions.svg?style=flat-square)](https://packagist.org/packages/juniorfontenele/laravel-exceptions)

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
composer require juniorfontenele/laravel-exceptions
```

### Quick Install (Recommended)

Run the interactive installation command:

```bash
php artisan laravel-exceptions:install
```

This will guide you through publishing assets, migrations, configuration, and running migrations.

### Manual Installation

**Important:** You must publish the package assets before using it:

```bash
php artisan vendor:publish --tag="laravel-exceptions-assets"
```

Optionally, publish and run migrations (if you want to customize them):

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

## Configuration

Main options in `config/laravel-exceptions.php`:

```php
return [
    // Custom error view
    'view' => 'laravel-exceptions::error',
    
    // Convert unhandled exceptions to AppException
    'convert_exceptions' => true,
    
    // Render custom exceptions even in debug mode
    'render_in_debug' => false,
    
    // Automatic context providers
    'context_providers' => [
        // AppExceptionContextProvider, UserContextProvider, etc.
    ],
    
    // Storage channels
    'channels' => [
        'database' => Database::class,
    ],
    
    // Ignored exceptions
    'ignored_exceptions' => [
        AuthenticationException::class,
        ValidationException::class,
    ],
];
```

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
