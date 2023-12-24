<?php

namespace SimonMarcelLinden\JWT\Routes;

trait RouteMixin {
    public function registerRoutes() {
        $router = $this->app->router;

		$router->get('/health', function () use ($router) {
			return $router->app->version();
		});

		$router->group(['prefix' => '{any:.*}'], function ($router) {
			$router->get('/', function() {
				// Handle the request
				return response('Page not found', 404);
			});
		});
    }
}
