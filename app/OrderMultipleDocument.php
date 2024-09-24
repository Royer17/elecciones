<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class OrderMultipleDocument extends Model {
	protected $table = 'order_multiple_document';

	public $timestamps = true;

	protected $fillable = [
		'parent_order_id',
		'order'
	];

	public function order() {
		return $this->belongsTo('sisVentas\Order');
	}

}
