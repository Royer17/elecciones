<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailOrder extends Model {
	use SoftDeletes;
		
	protected $table = 'details_order';

	protected $fillable = [

	];

	public function office() {
		return $this->belongsTo('sisVentas\Office', 'office_id');
	}
	
	public function office_origen() {
		return $this->belongsTo('sisVentas\Office', 'office_id_origen');
	}

	public function state() {
		return $this->belongsTo('sisVentas\DocumentState', 'status');
	}

	public function order() {
		return $this->belongsTo('sisVentas\Order', 'order_id');
	}

	public function user() {
		return $this->belongsTo('sisVentas\User', 'user_id');
	}
	
	//$detalle = DetailOrder::with('order')->find(1);
	//detaile->order->code;

}
