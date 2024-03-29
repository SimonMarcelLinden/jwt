<?php

return [

	'defaults' => [
		'guard' => 'jwt',
		'passwords' => 'users',
	],

	/*
	|--------------------------------------------------------------------------
	| Authentication Guards
	|--------------------------------------------------------------------------
	|
	*/
	'guards' => [
		'jwt' => [
			'driver' => 'jwt',
			'provider' => 'jwt'
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Providers
	|--------------------------------------------------------------------------
	|
	| Specify the various providers used throughout the package.
	|
	*/
	'providers' => [
		'jwt' => [
			'driver' => 'jwt',
			'model' => SimonMarcelLinden\JWT\Models\User::class,
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Route Activation
	|--------------------------------------------------------------------------
	|
	| This option allows you to globally enable or disable the routes provided
	| by this package. By default, all routes are enabled. Set this value
	| to 'false' to deactivate all routes specific to this package.
	| This is useful if you want to selectively use the package's features
	| or avoid conflicts with existing routes in your application.
	|
	*/

	'enable_routes' => true,

	'auth_method' => env('AUTH_METHOD', 'database'), // Standardmäßig 'database' oder 'ldap'
	'ldap' => [
		'host' => env('LDAP_HOST'),
		'connection' => env('LDAP_CONNECTION'),
		'username' => env('LDAP_USERNAME'),
		'password' => env('LDAP_PASSWORD'),
		'port' => env('LDAP_PORT'),
		'base' => env('LDAP_BASE_DN'),
	],
];
