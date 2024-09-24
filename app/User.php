<?php

namespace sisVentas;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

	protected $table = 'users';

	protected $primaryKey = 'id';
	
	protected $fillable = [
		'name', 'email', 'password', 'entity_id', 'role_id', 'sigla'
	];

	protected $hidden = [
		'password', 'remember_token',
	];

	public $timestamps = true;

	public function entity() {
		return $this->belongsTo('sisVentas\Entity', 'entity_id');
	}
}
