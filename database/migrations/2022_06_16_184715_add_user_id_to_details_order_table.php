<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToDetailsOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('details_order', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->after('attached_file');
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
            $table->dropColumn('user_id');
        });
    }
}
