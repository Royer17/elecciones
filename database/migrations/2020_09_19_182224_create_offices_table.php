<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOfficesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('offices', function (Blueprint $table) {
			$table->increments('id');
			$table->string('code')->nullable();
			$table->string('name');
			$table->string('sigla')->nullable();

			$table->unsignedInteger('entity_id');
			$table->unsignedInteger('upper_office_id');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('offices');
	}
}
