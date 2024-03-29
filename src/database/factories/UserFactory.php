<?php

namespace SimonMarcelLinden\JWT\database\factories;

// use App\Models\User;
use SimonMarcelLinden\JWT\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class UserFactory extends Factory {
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = User::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition() {
		return [
			"firstname" => $this->faker->firstName,
			"lastname" => $this->faker->lastName,
			'email' => $this->faker->unique()->safeEmail,
			"email_verified_at" => Carbon::now(),
			'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
		];
	}
}
