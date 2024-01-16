<?php

namespace SimonMarcelLinden\JWT\Auth\Guards;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use SimonMarcelLinden\JWT\Models\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * The JWTGuard class implements a custom authentication guard using JWT (JSON Web Tokens).
 * This guard is responsible for handling user authentication and validation using JWTs.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 */
class JWTGuard implements Guard {
	use GuardHelpers;

	/**
	 * @var Request The current request instance.
	 */
	protected $request;

	/**
	 * @var UserProvider The user provider implementation.
	 */
	protected $provider;

	/**
	 * @var string Your secret key for JWT tokens.
	 */
	protected $key;

	/**
	 * Create a new JWTGuard instance.
	 *
	 * @param UserProvider $provider The user provider.
	 * @param Request $request The current request.
	 * @param string $key The secret key for JWT.
	 */
	public function __construct(UserProvider $provider, Request $request, $key) {
		$this->provider = $provider;
		$this->request = $request;
		$this->key = $key;
	}

	/**
	 * Attempt to authenticate a user using the given credentials.
	 *
	 * @param array $credentials Authentication credentials.
	 * @return mixed
	 */
	public function attempt(array $credentials = []) {
		$user = $this->provider->retrieveByCredentials($credentials);

		if ($user && $this->provider->validateCredentials($user, $credentials)) {
			$this->setUser($user);
			return $this->generateJwtToken($user);
		}

		return false;
	}

	/**
	 * Generate a JWT token for a given user.
	 *
	 * @param Authenticatable $user The user instance.
	 * @return string The generated JWT token.
	 */
	protected function generateJwtToken(Authenticatable $user) {
		$payload = [
			'iss' => "jwt_issuer",					// Token issuer
			'sub' => $user->getAuthIdentifier(),	// Subject ID (usually the User ID)
			'iat' => time(), 						// Token creation time
			'exp' => (time() + 60 * 60) * 1000, 	// Token expiry time (e.g., 1 hour)
		];

		return JWT::encode($payload, $this->key, 'HS256');
	}

	/**
	 * Create a new JWT token for a given user.
	 *
	 * This method is a wrapper around the generateJwtToken method, providing a public interface to
	 * generate a new JWT token for a given user.
	 *
	 * @param Authenticatable $user The user instance.
	 * @return string The generated JWT token.
	 */
	public function createNewToken(Authenticatable $user) {
		return $this->generateJwtToken($user);
	}

	/**
	 * Extract JWT token from the request header.
	 *
	 * @return string|null The JWT token if present in the request header.
	 */
	protected function getTokenFromRequest() {
		return $this->request->bearerToken();
	}

	/**
	 * Get the user ID from the JWT token.
	 *
	 * @param string $token The JWT token.
	 * @return int The user ID decoded from the token.
	 */
	protected function getUserIdFromToken($token) {
		$key = $this->key;
		$decoded = JWT::decode($token, new Key($key, 'HS256'));
		return $decoded->sub;
	}

	/**
	 * Check if the current user is authenticated.
	 *
	 * @return bool
	 */
	public function check() {
		return !is_null($this->user());
	}

	/**
	 * Check if the current user is a guest.
	 *
	 * @return bool
	 */
	public function guest() {
		return !$this->check();
	}

	/**
	 * Get the currently authenticated user.
	 *
	 * @return User|null
	 */
	public function user() {
		if (!is_null($this->user)) {
			return $this->user;
		}

		$token = $this->getTokenFromRequest();
		if ($token) {
			$userId = $this->getUserIdFromToken($token);
			$this->user = $this->provider->retrieveById($userId);
		}

		return $this->user;
	}

	/**
	 * Get the ID of the authenticated user.
	 *
	 * @return mixed
	 */
	public function id() {
		if ($user = $this->user()) {
			return $user->getAuthIdentifier();
		}
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param array $credentials Authentication credentials.
	 * @return bool
	 */
	public function validate(array $credentials = []) {
		// Todo: Implement validation logic
		return true;
	}

	/**
	 * Set the current user.
	 *
	 * @param Authenticatable $user The user to set.
	 * @return $this
	 */
	public function setUser(Authenticatable $user) {
		$this->user = $user;
		return $this;
	}
}
