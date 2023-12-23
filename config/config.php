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
	]
];
