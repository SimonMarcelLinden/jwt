<?php

namespace SimonMarcelLinden\JWT;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\Factory;

use SimonMarcelLinden\JWT\Auth\Providers\JwtUserProvider;
use SimonMarcelLinden\JWT\Auth\Guards\JWTGuard;

use SimonMarcelLinden\JWT\Console\Commands\JWTInstallCommand;
use SimonMarcelLinden\JWT\Console\Commands\JWTKeyCommand;
use SimonMarcelLinden\JWT\Console\Commands\JWTRouteCommand;

use SimonMarcelLinden\JWT\Http\Middleware\CorsMiddleware;
use SimonMarcelLinden\JWT\Http\Middleware\JWTAuthMiddleware;
use SimonMarcelLinden\JWT\Http\Middleware\CheckPermission;
use SimonMarcelLinden\JWT\Routes\RouteMixin;

/**
 * JWTServiceProvider is a service provider for integrating JWT authentication into Laravel.
 * It registers JWTGuard and JwtUserProvider for handling authentication and user retrieval.
 * This provider also configures necessary middleware, console commands, and routes for the JWT functionality.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 *
 */
class JWTServiceProvider extends ServiceProvider {
	use RouteMixin;

	/**
	 * Bootstrap the application services.
	 * This method extends Laravel's Auth to use JWTGuard and JwtUserProvider. It also sets up
	 * the factory naming scheme, global and route-specific middleware, and console commands for JWT.
	 *
	 * @return void
	 */
	public function boot(): void {
		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
		$this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'migrations');

		Factory::guessFactoryNamesUsing(function (string $modelName) {
			return 'SimonMarcelLinden\\JWT\\Database\\Factories\\' . class_basename($modelName) . 'Factory';
		});

		Auth::extend('jwt', function ($app, $name, array $config) {
			$key = env('JWT_SECRET');
			return new JWTGuard(Auth::createUserProvider($config['provider']), $app['request'], $key);
		});

		Auth::provider('jwt', function ($app, array $config) {
			return new JwtUserProvider();
		});

		// Globale Middleware
		$this->app->middleware([
			CorsMiddleware::class,
			JWTAuthMiddleware::class,
		]);

		$this->app->routeMiddleware([
			'permission' => CheckPermission::class,
		]);

		if ($this->app->runningInConsole()) {
			$this->commands([
				JWTInstallCommand::class,
				JWTKeyCommand::class,
				JWTRouteCommand::class,
			]);
		}

		if ($this->app['config']->get('jwt.enable_routes', true)) {
			$this->registerRoutes();
		}
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
