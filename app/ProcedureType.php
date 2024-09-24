<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class ProcedureType extends Model {
	protected $table = 'procedure_types';

	protected $fillable = [
		'code',
		'name',
	];

}
