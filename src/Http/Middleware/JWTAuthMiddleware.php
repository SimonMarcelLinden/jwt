<?php

namespace SimonMarcelLinden\JWT\Http\Middleware;

use SimonMarcelLinden\JWT\Exceptions\ResponseException;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

/**
 * JWTAuthMiddleware extends the default authentication middleware to incorporate JWT.
 * It handles JWT token validation and user authentication for incoming requests.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 *
 */
class JWTAuthMiddleware extends Middleware {
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
	 * Validates JWT token and sets user for authenticated requests.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @param mixed ...$guards
	 * @return mixed
	 */
	public function handle($request, Closure $next, ...$guards): mixed {
		if ($this->shouldPassThrough($request)) {
			return $next($request);
		}

		if (!($token = $request->bearerToken())) {
			return response()->json(['message' => 'Token not provided'], 401);
		}

		// Todo: Check if token valid and user exist
		try {
			$key = env('JWT_SECRET');
			$credentials = JWT::decode($token, new Key($key, 'HS256'));
		} catch (ExpiredException $e) {
			return response()->json(['message' => 'Provided token is expired.'], 401);
		} catch (Exception $e) {
			return response()->json(['message' => 'An error occurred while decoding token.'], 401);
		}

		$user = $this->auth->guard()->getProvider()->retrieveById($credentials->sub);

		if (!$user) {
			throw new ResponseException("User not found based on the provided criteria", 404);
		}

		$this->auth->guard()->setUser($user);
		$request->user = $user;

		return $next($request);
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
