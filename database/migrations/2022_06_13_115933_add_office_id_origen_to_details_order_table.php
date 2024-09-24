<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfficeIdOrigenToDetailsOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('details_order', function (Blueprint $table) {
            $table->unsignedInteger('office_id_origen')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('details_order', function (Blueprint $table) {
            $table->dropColumn('office_id_origen');
        });
    }
}
