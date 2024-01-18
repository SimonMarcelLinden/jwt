<?php

namespace SimonMarcelLinden\JWT\Auth\Providers;

use SimonMarcelLinden\JWT\Exceptions\ResponseException;
use SimonMarcelLinden\JWT\Models\User;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

/**
 * JwtUserProvider implements the UserProvider interface for JWT based authentication.
 * This class provides methods for retrieving and validating user data for authentication purposes.
 *
 * @param mixed $identifier User identifier.
 * @return User|null
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 *
 */
class JwtUserProvider implements UserProvider {

	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param mixed $identifier The user identifier.
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null The user model or null.
	 */
	public function retrieveById($identifier): ?Authenticatable {
		return User::find($identifier);
	}

	/**
	 * Retrieve a user by their unique identifier and "remember me" token.
	 * This method is not supported in JWT based authentication.
	 *
	 * @param mixed $identifier The user identifier.
	 * @param string $token The remember token.
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null Always returns null.
	 */
	public function retrieveByToken($identifier, $token): Authenticatable|null {
		return null;
	}

	/**
	 * Update the "remember me" token for the user.
	 * This method is not supported in JWT based authentication.
	 *
	 * @param \Illuminate\Contracts\Auth\Authenticatable $user The user instance.
	 * @param string $token The new remember token.
	 * @return void
	 */
	public function updateRememberToken(Authenticatable $user, $token): void {
		// Method not supported in JWT based authentication
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param array $credentials Authentication credentials.
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null The user model or null.
	 */
	public function retrieveByCredentials(array $credentials): ?Authenticatable {
		if (empty($credentials['username'])) {
			return null;
		}

		return User::where('username', $credentials['username'])->first();
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param \Illuminate\Contracts\Auth\Authenticatable $user The user instance.
	 * @param array $credentials Authentication credentials.
	 * @return bool True if credentials are valid, false otherwise.
	 */
	public function validateCredentials(Authenticatable $user, array $credentials): bool {
		$plain = $credentials['password'];

		return Hash::check($plain, $user->getAuthPassword());
	}

	/**
	 * Create a user from LDAP credentials.
	 *
	 * This method attempts to create a new user based on LDAP credentials.
	 * It checks for an existing user first and then creates a new user if not found.
	 *
	 * @param array $credentials LDAP credentials.
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null The user model or null on failure.
	 * @throws ResponseException If user creation fails.
	 */
	public function createUserFromLdap(array $credentials): ?Authenticatable {
		$existingUser = User::where('username', $credentials['username'])->first();

		if ($existingUser) {
			return $existingUser;
		}

		$requiredFields = ['username', 'firstname', 'lastname', 'email', 'password'];
		foreach ($requiredFields as $field) {
			if (!array_key_exists($field, $credentials)) {
				throw new \InvalidArgumentException("Missing credential field: $field");
			}
		}

		try {
			$user = new User;
			$user->username = $credentials['username'];
			$user->firstname = $credentials['firstname'];
			$user->lastname = $credentials['lastname'];
			$user->email = $credentials['email'];

			$user->password = Hash::make($credentials['password']);

			$user->save();

			return $user;
		} catch (\Throwable $th) {
			throw new ResponseException("User creation failed: " . $th->getMessage(), 400);
		}
	}
}
