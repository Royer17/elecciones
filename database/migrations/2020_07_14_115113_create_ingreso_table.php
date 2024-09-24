<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIngresoTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ingreso', function (Blueprint $table) {
			$table->increments('idingreso');
            $table->unsignedInteger('idproveedor');
            $table->string('tipo_comprobante');
            $table->string('serie_comprobante')->nullable();
            $table->string('num_comprobante');
            $table->datetime('fecha_hora');
            $table->decimal('impuesto', 4, 2);
            $table->string('estado');
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
		Schema::drop('ingreso');
	}
}
