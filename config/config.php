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
			'model' => App\User::class,
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
];
