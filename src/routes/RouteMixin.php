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
     * - A health check route.
     * - A catch-all route for handling unmatched requests.
     */
	public function registerRoutes() {
		// Get the router instance from the application.
        $router = $this->app->router;

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
		});
	}
}
