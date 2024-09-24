<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleIngresoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ingreso', function (Blueprint $table) {
            $table->increments('iddetalle_ingreso');
            $table->unsignedInteger('idingreso');
            $table->unsignedInteger('idarticulo');
            $table->integer('cantidad');
            $table->decimal('precio_compra', 11, 2);
            $table->decimal('precio_venta', 11, 2);
            $table->decimal('precio_venta2', 11, 2);
            $table->decimal('precio_venta3', 11, 2);
            $table->decimal('precio_venta4', 11, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('detalle_ingreso');
    }
}
