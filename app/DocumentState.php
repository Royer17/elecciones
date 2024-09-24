<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class DocumentState extends Model {
	protected $table = 'document_statuses';

	public $timestamps = true;

	protected $fillable = [
		'code',
		'name',
	];

}
