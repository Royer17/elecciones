<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVentaTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('venta', function (Blueprint $table) {
			$table->increments('idventa');
			$table->unsignedInteger('idcliente');
			$table->string('tipo_comprobante');
			$table->string('serie_comprobante');
			$table->string('num_comprobante');
			$table->datetime('fecha_hora');
			$table->decimal('impuesto', 4, 2);
			$table->decimal('total_venta', 11, 2);
			$table->string('estado'); //1 pendiente, 2 confirmado
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
		Schema::drop('venta');
	}
}
