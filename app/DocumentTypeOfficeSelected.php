<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class DocumentTypeOfficeSelected extends Model {
	protected $table = 'document_type_office_selected';

	public $timestamps = true;

	protected $fillable = [
		'document_type_id',
		'office_id',
	];

}
