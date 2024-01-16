<?php

namespace SimonMarcelLinden\JWT\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * CheckPermission Middleware.
 *
 * This middleware is responsible for checking if the authenticated user has the required permission
 * to access a specific route or resource. It's designed to work with JWT authentication.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 */
class CheckPermission {
	/**
	 * Routes that are exempt from JWT authentication.
	 *
	 * @var string[]
	 */
	protected $except = [
		'api/login',
		'api/logout'
	];

	/**
	 * Handle an incoming request.
	 *
	 * This method checks if the authenticated user has the required permission to proceed with the request.
	 * If the user has the necessary permission, the request is passed further down the application pipeline.
	 * Otherwise, a response with 'Access denied' message is returned.
	 *
	 * @param \Illuminate\Http\Request $request The incoming HTTP request.
	 * @param Closure $next The next middleware in the pipeline.
	 * @param string|null $ability The required permission to check for.
	 * @return mixed The response object or the next middleware.
	 */
	public function handle($request, Closure $next, $ability = null): mixed {
		if ($this->shouldPassThrough($request)) {
			return $next($request);
		}

		if (Auth::check() && (Auth::user()->permissions->pluck('slug')->contains('admin'))) {
			return $next($request);
		}

		foreach (Auth::user()->permissions as $permission) {
			if ($permission->slug === $ability || $permission->children->pluck('slug')->contains($ability)) {
				return $next($request);
			}
		}

		return response()->json(['message' => 'Access denied. You do not have the necessary permission to perform this action.'], 401);
	}

	/**
	 * Determines if the request should bypass JWT authentication.
	 *
	 * This method checks if the current request matches any of the routes specified in the $except array.
	 * If a match is found, the request is allowed to bypass JWT authentication checks.
	 *
	 * @param \Illuminate\Http\Request $request The incoming HTTP request.
	 * @return bool True if the request should bypass authentication, false otherwise.
	 */
	protected function shouldPassThrough($request): bool {
		foreach ($this->except as $route) {
			if ($request->is($route)) {
				return true;
			}
		}

		return false;
	}
}
