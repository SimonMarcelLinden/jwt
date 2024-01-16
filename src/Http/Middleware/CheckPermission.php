<?php

namespace SimonMarcelLinden\JWT\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

	public function handle($request, Closure $next, $ability = null) {
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
	 * @param \Illuminate\Http\Request $request
	 * @return bool
	 */
	protected function shouldPassThrough($request) {
		foreach ($this->except as $route) {
			if ($request->is($route)) {
				return true;
			}
		}

		return false;
	}
}
