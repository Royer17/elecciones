<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOutstandingToCategoriaTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('categoria', function (Blueprint $table) {
			$table->boolean('outstanding')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('categoria', function (Blueprint $table) {
			$table->dropColumn('outstanding');
		});
	}
}
