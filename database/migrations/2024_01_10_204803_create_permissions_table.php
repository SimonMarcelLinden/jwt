<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration {
	public function up() {
		Schema::create('permissions', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('slug');
			$table->string('name');
			$table->text('description')->nullable();
			$table->uuid('parent_id')->nullable();
			$table->foreign('parent_id')->references('id')->on('permissions')->onDelete('cascade');

			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('permissions');
	}
}
