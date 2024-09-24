<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangePreciosVentasInDetalleIngresoTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('detalle_ingreso', function (Blueprint $table) {
			// $table->decimal('precio_venta2', 11, 2)->nullable()->change();
			// $table->decimal('precio_venta3', 11, 2)->nullable()->change();
			// $table->decimal('precio_venta4', 11, 2)->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('detalle_ingreso', function (Blueprint $table) {
			//
		});
	}
}
