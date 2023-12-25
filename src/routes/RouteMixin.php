<?php

namespace SimonMarcelLinden\JWT\Routes;

/**
 * Provides a trait to register JWT related routes in a Lumen application.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 *
 */
trait RouteMixin {
	/**
	 * Registers routes for the application.
	 *
	 * This method sets up a couple of routes for the application:
	 * - API routes for JWT authentication.
	 * - A health check route.
	 * - A catch-all route for handling unmatched requests.
	 */
	public function registerRoutes() {
		// Get the router instance from the application.
		$router = $this->app->router;

		// Register API routes related to JWT authentication.
		$this->apiRoutes($router);

		/**
		 * Registers a GET route for '/health'.
		 *
		 * This route returns the version of the Laravel/Lumen application.
		 * It can be used as a simple health check endpoint.
		 */
		$router->get('/health', function () use ($router) {
			return $router->app->version();
		});

		/**
		 * Sets up a catch-all route group.
		 *
		 * This group catches all routes that haven't been matched by previous route definitions.
		 * It only supports the GET method and returns a 404 response for unmatched routes.
		 */
		$router->group(['prefix' => '{any:.*}'], function ($router) {
			$router->get('/', function() {
				// Handle the request
				return response('Page not found', 404);
			});
			$router->post('/', function() {
				// Handle the request
				return response('Page not found', 404);
			});
		});
	}

	/**
	 * Registers API routes for JWT authentication.
	 *
	 * This method sets up routes under the 'api' prefix. Currently, it includes:
	 * - A route for user login which authenticates and provides a JWT.
	 *
	 * @param \Laravel\Lumen\Routing\Router $router The router instance to define routes on.
	 */
	private function apiRoutes($router) {
		$router->group(['prefix' => 'api'], function () use ($router) {
			$router->post('login', ['uses' => 'SimonMarcelLinden\JWT\Http\Controllers\JWTAuthController@login', 'as' => 'login']);
			$router->get('me', ['uses' => 'SimonMarcelLinden\JWT\Http\Controllers\JWTAuthController@me', 'as' => 'me']);
		});
	}
}
