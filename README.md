# JWT for Laravel/Lumen

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

Enhance your Laravel and Lumen applications with this efficient JWT package, designed to streamline user authentication using JSON Web Tokens. Experience robust security with minimal complexity. A perfect choice for developers seeking a user-friendly, secure authentication solution.

## Lumen Installation

**Install via Composer**

``` bash
$ composer require simonmarcellinden/jwt
```
---

**Install config File**

Use ```php artisan jwt:config``` for install the config file automatically.

Alternatively, copy the ```config``` file from ```simonmarcellinden/jwt/config/config.php```. to the ```config``` folder of your Lumen application and rename it to ```jwt.php```.

Register your config by adding the following in the bootstrap/app.php before middleware declaration.
``` bash
$app->configure('jwt');
```
---

**Bootstrap file changes**
Add the following snippet to the ```bootstrap/app.php``` file under the providers section as follows:

``` bash
$app->register(\SimonMarcelLinden\JWT\JWTServiceProvider::class);
```
---
**Generate secret key**
``` bash
php artisan jwt:generate
```
This will update your ```.env``` file with something like JWT_SECRET=AABBCCDDEE

---
**Activate or deactivate JWT routes**
``` bash
php artisan jwt:routes {action}
```
This option allows you to globally enable or disable the routes provided by this package. By default, all routes are enabled.

---
**Update Seeder and run the migrations** 

Update your main seeder
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$this->call('SimonMarcelLinden\\JWT\\database\\seeders\\UserSeeder');
		$this->call('SimonMarcelLinden\\JWT\\database\\seeders\\PermissionSeeder');
	}
}
```

and run the migrations to add the required tables to your database.
```bash
php artisan migrate:fresh --seed
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
