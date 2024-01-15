<?php

namespace SimonMarcelLinden\JWT\database\seeders;

use SimonMarcelLinden\JWT\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 */
	public function run(): void {
		User::create([
			"firstname" => "John",
			"lastname" => "Doe",
			"email" => "admin@lumen-template.de",
			"email_verified_at" => Carbon::now(),
			'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
		]);

		\SimonMarcelLinden\JWT\Models\User::factory(100)->create();
	}
}
