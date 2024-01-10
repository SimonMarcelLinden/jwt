<?php

namespace SimonMarcelLinden\JWT\Auth\Providers;

use SimonMarcelLinden\JWT\Models\User;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

/**
 * Retrieve a user by their unique identifier.
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
	public function retrieveById($identifier) {
		return User::find($identifier);
	}

	/**
	 * Update the "remember me" token for the user.
	 *
	 * @param Authenticatable $user The user instance.
	 * @param string $token The new remember token.
	 * @return void
	 */
	public function updateRememberToken(Authenticatable $user, $token) {
		// Todo: Update remember token
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param array $credentials Authentication credentials.
	 * @return User|null
	 */
	public function retrieveByCredentials(array $credentials) {
		if (empty($credentials['email'])) {
			return null;
		}

		return User::where('email', $credentials['email'])->first();
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param Authenticatable $user The user instance.
	 * @param array $credentials Authentication credentials.
	 * @return bool
	 */
	public function validateCredentials(Authenticatable $user, array $credentials) {
		$plain = $credentials['password'];

		return Hash::check($plain, $user->getAuthPassword());
	}
}
