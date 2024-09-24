<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriaTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('categoria', function (Blueprint $table) {
			$table->increments('idcategoria');
			$table->string('nombre');
			$table->text('descripcion');
			$table->boolean('condicion')->default(true);
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
		Schema::drop('categoria');
	}
}
