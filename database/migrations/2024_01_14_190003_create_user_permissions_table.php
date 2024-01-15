<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionsTable extends Migration {
	public function up() {
		Schema::create('permission_user', function (Blueprint $table) {
			$table->uuid('permission_id');
			$table->uuid('user_id');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

			$table->primary(['user_id', 'permission_id']);

			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('user_permissions');
	}
}
