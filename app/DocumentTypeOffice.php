<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class DocumentTypeOffice extends Model {
	protected $table = 'document_type_office';

	public $timestamps = true;

	protected $fillable = [
		'document_type_id',
		'office_id',
		'start_with',
	];

}
