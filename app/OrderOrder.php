<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderOrder extends Model {
	use SoftDeletes;
	
	protected $table = 'order_order';
	protected $dates = ['deleted_at'];

	protected $fillable = [

	];

	public function parent_order() {
		return $this->belongsTo('sisVentas\Order', 'parent_order_id');
	}
}
