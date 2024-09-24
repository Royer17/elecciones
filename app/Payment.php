<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model {

	use SoftDeletes;
	
	protected $table = 'payments';

	protected $guarded = [];

	public $timestamps = true;

	public function details() {
		return $this->hasMany('sisVentas\DetailOrder', 'payment_id');
	}
	
}
