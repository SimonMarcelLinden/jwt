<?php

namespace SimonMarcelLinden\JWT\Http\Controllers;

use App\Http\Controllers\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\JsonResponse as HttpJsonResponse;

/**
 * JWT Authentication Controller.
 *
 * This controller handles JWT (JSON Web Token) authentication for the application.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 *
 */
class JWTAuthController extends Controller {
	/**
	 * Constructor for JWTAuthController.
	 */
	public function __construct() {
		// Parent constructor is called implicitly.
	}

	/**
	 * Generate a JSON response with token details.
	 *
	 * This method constructs a JSON response that includes the JWT, its type, the authenticated user, and the time
	 * remaining until the token expires. It decodes the given JWT to calculate the expiration time.
	 *
	 * @param string $token The JWT token to be included in the response.
	 * @return \Illuminate\Http\JsonResponse A JSON response containing the token details.
	 * @throws \InvalidArgumentException If the JWT_SECRET is not set in the environment.
	 */
	protected function jsonResponse($token) {
		// Retrieve the JWT secret key from the environment variables.
		$key = env('JWT_SECRET');

		// Check if the JWT secret key is set.
		if (!$key) {
			throw new \InvalidArgumentException('JWT_SECRET is not set.');
		}

		// Decode the JWT using the Firebase JWT library and a specified algorithm ('HS256').
		$decodedToken = JWT::decode($token, new Key($key, 'HS256'));

		// Calculate the remaining time until the token expires in seconds.
		$expiresIn = $decodedToken->exp;

		// Construct and return a JSON response with the token details.
		return response()->json([
			'access_token' => $token,			// The JWT token.
			'token_type' => 'bearer',    		// The type of the token.
			'user' => auth()->user(),	// The authenticated user.
			'expires_in' => $expiresIn 		// The time in seconds until the token expires.
		]);
	}

	/**
	 * Authenticate and provide a JWT.
	 *
	 * This method handles the login functionality. It authenticates a user based on the provided credentials
	 * and generates a JWT (JSON Web Token) for the authenticated session. It validates the incoming request for
	 * email and password. If the credentials are invalid, it returns a 401 Unauthorized response. On successful
	 * authentication, it returns a JSON response with the generated JWT.
	 *
	 * @param Request $request HTTP request with credentials ('email' and 'password').
	 * @return JsonResponse Returns a JSON response with the JWT on successful authentication or an error message on failure.
	 *
	 */
	public function login(Request $request) {
		$this->validate($request, [
			'email' => 'required|string',
			'password' => 'required|string',
		]);

		$credentials = $request->only(['email', 'password']);

		if (!$token = Auth::attempt($credentials)) {
			return response()->json(['message' => 'Invalid credentials'], 401);
		}

		return $this->jsonResponse($token);
	}

	/**
	 * Invalidate the current JWT.
	 *
	 * This method handles logging out a user by invalidating the current JWT token.
	 * After this action, the token can no longer be used for authentication.
	 *
	 * @param Request $request HTTP request.
	 * @return void The method does not return a value.
	 */
	public function logout(Request $request): void {
		// JWT invalidation logic to be implemented.
	}

	/**
	 * Retrieve the currently authenticated user.
	 *
	 * This method checks if there is a user currently authenticated. If a user is authenticated, it returns
	 * a JSON response with the user's details. If no user is authenticated, it returns a JSON response with
	 * an error message indicating invalid credentials.
	 *
	 * @param Request $request The incoming HTTP request.
	 * @return \Illuminate\Http\JsonResponse A JSON response containing either the authenticated user's details or an error message.
	 *
	 */
	public function me(Request $request): HttpJsonResponse {
		if (Auth::check())
			return response()->json(auth()->user());
		else
			return response()->json(['message' => 'Invalid credentials'], 401);
	}

	/**
	 * Refresh the current JWT token.
	 *
	 * This method refreshes the current JWT, potentially extending the session or modifying the token claims.
	 *
	 * @param Request $request HTTP request.
	 * @return \Illuminate\Http\JsonResponse A JSON response containing either the authenticated user's details or an error message.
	 */
	public function refresh(Request $request) {
		// Todo: Invalidate old token e.g.: save it in the database and query it
		if (!($user = Auth::check())) {
			return response()->json(['message' => 'Invalid token'], 401);
		}

		$user = Auth::user();
		$newToken = auth()->guard()->createNewToken($user);

		return $this->jsonResponse($newToken);
	}
}
