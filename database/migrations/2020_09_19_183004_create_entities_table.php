<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEntitiesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('entities', function (Blueprint $table) {
			$table->increments('id');
			$table->string('code')->nullable();
			$table->integer('type_document');
			$table->string('name');
			$table->string('paternal_surname')->nullable();
			$table->string('maternal_surname')->nullable();
			$table->string('identity_document');
			$table->string('ruc')->nullable();
			$table->unsignedInteger('profession_id');
			$table->string('address')->nullable();
			$table->string('cellphone')->nullable();
			$table->string('email')->nullable();
			$table->unsignedInteger('office_id');
			$table->boolean('status')->default(true);

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
		Schema::drop('entities');
	}
}
