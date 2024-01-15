<?php

namespace SimonMarcelLinden\JWT\database\seeders;

// use App\Models\User;
use SimonMarcelLinden\JWT\Models\Permission;
use Illuminate\Database\Seeder;
use SimonMarcelLinden\JWT\Models\User;

class PermissionSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 */
	public function run(): void {

		$admin = Permission::create([
			'slug' => 'admin',
			'name' => 'Administrator',
			'description' => 'This permission grants full access to all system functionalities and settings. It enables the management of user accounts, modification of security settings, and access to all administrative features. Ideal for system administrators and IT staff.',
		]);

		$timeTracking = Permission::create([
			'slug' => 'timeTracking',
			'name' => 'Time Tracking',
			'description' => 'Allows managing and monitoring of time tracking data. Users with this permission can generate reports, view time logs, and make basic settings adjustments for time tracking. Suitable for team leaders and HR management.',
		]);

		Permission::create([
			'slug' => 'timeTracking:edit',
			'name' => 'Edit Time Tracking',
			'description' => 'Permits the editing of existing time tracking entries. Users can correct, update, or delete entries. This permission is intended for managers or employees responsible for the accuracy of time tracking data.',
			'parent_id' => $timeTracking->id
		]);

		Permission::create([
			'slug' => 'timeTracking:register',
			'name' => 'Register Time Tracking',
			'description' => 'Authorizes the registration of new time tracking entries. Users can add new work hours and record information on specific activities or projects. Ideal for employees who need to log their own work hours.',
			'parent_id' => $timeTracking->id
		]);

		$user = User::where('email', 'admin@lumen-template.de')->first();
		$adminPermission = Permission::where('slug', 'admin')->first();

		if ($user && $adminPermission) {
			$user->permissions()->attach($admin->id);
		}
	}
}
