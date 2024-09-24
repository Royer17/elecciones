<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDetailsOrderTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('details_order', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('order_id');
			$table->integer('status');
			$table->unsignedInteger('office_id');
			$table->text('observations')->nullable();
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
		Schema::drop('details_order');
	}
}
