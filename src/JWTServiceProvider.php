<?php

namespace SimonMarcelLinden\JWT;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;

use SimonMarcelLinden\JWT\Auth\Providers\JwtUserProvider;
use SimonMarcelLinden\JWT\Auth\Guards\JWTGuard;
use SimonMarcelLinden\JWT\Console\Commands\JWTCommand;

class JWTServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot(): void {

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
