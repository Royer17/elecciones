<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentTypesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('document_types', function (Blueprint $table) {
			$table->increments('id');
			$table->string('code')->nullable();
			$table->string('name');
			$table->string('sigla')->nullable();
			$table->boolean('status')->default(true);
			$table->timestamps();
			$table->softDeletes();
			//1 - php artisan make:migration create_docuemnt_types_table --create=document_types
			//2 - php artisan make:migration add_number_to_document_types_table --table=document_types
			
			//php artisan migrate
			//string, boolean, text, integer, decimal, date, datetime
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('document_types');
	}
}
