<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model {
	protected $table = 'professions';

	public $timestamps = true;

	protected $fillable = [
		'name',
		'code',
		'sigla',
	];
}