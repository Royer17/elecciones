<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model {
	protected $table = 'document_types';

	public $timestamps = true;

	protected $fillable = [
		'code',
		'name',
		'sigla',
		'status',
		'start_with',
		'is_multiple',
	];

	public function office() {
		return $this->hasOne('sisVentas\DocumentTypeOffice', 'document_type_id');
	}
	
	public function office_selected() {
		return $this->hasOne('sisVentas\DocumentTypeOfficeSelected', 'document_type_id');
	}

}
