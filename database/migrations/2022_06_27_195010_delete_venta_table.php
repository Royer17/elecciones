<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('venta');
        Schema::dropIfExists('persona');
        Schema::dropIfExists('procedure_types');
        Schema::dropIfExists('detalle_venta');
        Schema::dropIfExists('articulo');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
