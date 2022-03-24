# Laravel Request Logger

> Easily logs all request and response

[![Issues](https://img.shields.io/github/issues/zxygel0913/request-logger)](https://github.com/zxygel0913/request-logger)

## Installation

Require this package in your `composer.json` or install it by running:

```
composer require zxygel0913/request-logger
```

To start using Laravel, add the Service Provider to your `config/app.php`:

```php
'providers' => [
	// ...
	Zxygel0913\RequestLogger\RequestLoggerServiceProvider::class
]
```

Now, you should publish package's config file to your config directory by using following command:

```
php artisan vendor:publish
```
or
```
php artisan vendor:publish --tag=requestLogs
```
## Config

If you have published config file, you can change the default settings in `config/requestLogs.php` file:

```php
return [
    'channel' => 'daily', //Log Channel
    'logs' => [
        'headers' => true,
        'path' => true,
        'ip' => true,
        'request_method' => true,
        'request' => true,
        'response' => false,
        'request_except' => ['password'] //Hide specific request from logging
    ],
```
