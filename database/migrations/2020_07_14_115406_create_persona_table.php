<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonaTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('persona', function (Blueprint $table) {
			$table->increments('idpersona');
			$table->string('tipo_persona');
			$table->string('nombre');
			$table->string('tipo_documento')->nullable();
			$table->string('num_documento')->nullable();
			$table->string('direccion')->nullable();
			$table->string('telefono')->nullable();
			$table->string('email')->nullable();
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
		Schema::drop('persona');
	}
}
