<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model {
	use SoftDeletes;

	protected $table = 'entities';

	protected $fillable = [
		'code',
		'name',
		'paternal_surname',
		'maternal_surname',
		'identity_document',
		'ruc',
		'profession_id',
		'address',
		'cellphone',
		'email',
		'office_id',
		'status',
		'type_document',
	];

	public $timestamps = true;

	public function office()
	{
		return $this->belongsTo('sisVentas\Office', 'office_id');
	}

	public function profession()
	{
		return $this->belongsTo('sisVentas\Profession', 'profession_id');
	}

	public function orders()
	{
		return $this->hasMany('sisVentas\Order', 'entity_id');
	}

	public function user()
	{
		return $this->hasOne('sisVentas\User', 'entity_id');
	}

}
