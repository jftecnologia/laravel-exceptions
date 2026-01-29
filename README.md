# Exceptions on steroids for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/juniorfontenele/laravel-exceptions.svg?style=flat-square)](https://packagist.org/packages/juniorfontenele/laravel-exceptions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/juniorfontenele/laravel-exceptions/tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/juniorfontenele/laravel-exceptions/actions?query=workflow%3Atests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/juniorfontenele/laravel-exceptions/fix-php-code-style.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/juniorfontenele/laravel-exceptions/actions?query=workflow%3A"fix-php-code-style-issues"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/juniorfontenele/laravel-exceptions.svg?style=flat-square)](https://packagist.org/packages/juniorfontenele/laravel-exceptions)
<!--delete-->
---
This repo can be used to scaffold a Laravel package. Follow these steps to get started:

1. Press the "Use this template" button at the top of this repo to create a new repo with the contents of this skeleton.
2. Run "php ./configure.php" to run a script that will replace all placeholders throughout all the files.
3. Have fun creating your package.

<!--/delete-->
This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require juniorfontenele/laravel-exceptions
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-exceptions-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-exceptions-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-exceptions-views"
```

## Usage

```php
$variable = new JuniorFontenele\LaravelExceptions();
echo $variable->echoPhrase('Hello, JuniorFontenele!');
```

## Testing

```bash
composer test
```

## Credits

- [Junior Fontenele](https://github.com/juniorfontenele)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
