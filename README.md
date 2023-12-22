# JWT for Laravel/Lumen

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]

Enhance your Laravel and Lumen applications with this efficient JWT package, designed to streamline user authentication using JSON Web Tokens. Experience robust security with minimal complexity. A perfect choice for developers seeking a user-friendly, secure authentication solution.

## Installation

Via Composer

``` bash
$ composer require simonmarcellinden/jwt
```

Register the package's service provider in config/app.php. In Laravel versions 5.5 and beyond, this step can be skipped if package auto-discovery is enabled.

Open and add the service provider to `bootstrap/app.php`
```php
	$app->register(\SimonMarcelLinden\JWT\JWTServiceProvider::class);
```

### Publish the configurations
~~Run this on the command line from the root of your project:~~
```
$ no config needed
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email info@snerve.de instead of using the issue tracker.

[ico-version]: https://img.shields.io/packagist/v/simonmarcellinden/jwt
[ico-downloads]: https://img.shields.io/packagist/dt/SimonMarcelLinden/jwt

[link-packagist]: https://packagist.org/packages/simonmarcellinden/jwt
[link-downloads]: https://packagist.org/packages/simonmarcellinden/jwt
[link-travis]: https://travis-ci.org/simonmarcellinden/jwt
[link-author]: https://github.com/simonmarcellinden
[link-contributors]: ../../contributors
