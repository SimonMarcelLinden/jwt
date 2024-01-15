<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::table('users', function (Blueprint $table) {
			$table->string('lastname')->after('id');
			$table->string('firstname')->after('id');

			$table->softDeletes();

			$table->dropColumn('name');
			$table->dropColumn(['remember_token']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::table('users', function (Blueprint $table) {
			$table->id()->change();
			$table->string('name')->after('id');
			$table->stirng('remember_token')->after('password');
			;

			$table->dropColumn('lastname');
			$table->dropColumn('firstname');
			$table->dropSoftDeletes();
		});
	}
};
