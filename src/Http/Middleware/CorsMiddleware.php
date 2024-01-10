<?php

namespace SimonMarcelLinden\JWT\Http\Middleware;

use Closure;

/**
 * CORS Middleware.
 *
 * This middleware handles Cross-Origin Resource Sharing (CORS) requests for the application.
 * It sets various CORS headers to manage and allow cross-origin requests, ensuring the application
 * can interact safely with resources from different origins.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 */
class CorsMiddleware {
	/**
	 * Handle an incoming request.
	 *
	 * This method configures CORS headers on the response object for the incoming HTTP request.
	 * It specifically handles 'OPTIONS' requests separately, returning a default response for them.
	 * For other request types, it appends the CORS headers to the response object.
	 *
	 * @param \Illuminate\Http\Request $request The incoming HTTP request.
	 * @param Closure $next The next middleware in the pipeline.
	 * @return mixed The response object with CORS headers.
	 */
	public function handle($request, Closure $next): mixed {
		$headers = [
			'Access-Control-Allow-Origin' => '*',
			'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE, PATCH',
			'Access-Control-Allow-Credentials' => 'true',
			'Access-Control-Max-Age' => '86400',
			'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With'
		];

		if ($request->isMethod('OPTIONS')) {
			return response()->json('{"method":"OPTIONS"}', 200, $headers);
		}

		$response = $next($request);
		foreach ($headers as $key => $value) {
			$response->header($key, $value);
		}

		return $response;
	}
}
