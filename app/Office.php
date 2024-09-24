<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class Office extends Model {

	protected $table = 'offices';

	public $timestamps = true;

	protected $fillable = [
		'name',
		'code',
		'sigla',
		'entity_id',
		'upper_office_id',
		'status',
		'year',
		'is_for_all_years'
	];

	public function entity() {
		return $this->belongsTo('sisVentas\Entity', 'entity_id');
	}

	public function personal() {
		return $this->hasMany('sisVentas\Entity', 'office_id');
	}
	
	public function orders_1() {
		return $this->hasMany('sisVentas\Order', 'office_id');
	}

	public function details() {
		return $this->hasMany('sisVentas\DetailOrder', 'office_id');
	}

	public function orders_2() {
		return $this->hasMany('sisVentas\Order', 'office_id_origen');
	}

	public function document_types() {
		return $this->belongsToMany('sisVentas\DocumentType', 'document_type_office', 'office_id', 'document_type_id');
	}

	public function document_types_selected() {
		return $this->belongsToMany('sisVentas\DocumentType', 'document_type_office_selected', 'office_id', 'document_type_id');
	}
}
