<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {
	use SoftDeletes;
	
	protected $table = 'orders';
	protected $dates = ['deleted_at'];

	protected $fillable = [
		'document_type_id',
		'number',
		'folios',
		'subject',
		'notes',
		'priority_days',
		'date',
		'reference',
		'tupa_id',
		'order_type_id',
		'term',
	];

	public function document_type() {
		return $this->belongsTo('sisVentas\DocumentType', 'document_type_id');
	}

	public function tupa() {
		return $this->belongsTo('sisVentas\Tupa', 'tupa_id');
	}

	public function office() {
		return $this->belongsTo('sisVentas\Office', 'office_id');
	}

	public function entity() {
		return $this->belongsTo('sisVentas\Entity', 'entity_id');
	}

	public function details() {
		return $this->hasMany('sisVentas\DetailOrder', 'order_id');
	}

	public function debt_details() {
		return $this->hasMany('sisVentas\DetailOrder', 'order_id')->where('status', 0);
	}

	public function details_one() {
		return $this->hasMany('sisVentas\DetailOrder', 'order_id')->where('office_id_origen', 1);
	}

	public function details_two() {
		return $this->hasMany('sisVentas\DetailOrder', 'order_id')->where('office_id_origen', 2);
	}

	public function detail() {
		return $this->hasOne('sisVentas\DetailOrder', 'order_id');
	}
	
	public function detail_by_date_desc() {
		return $this->hasOne('sisVentas\DetailOrder', 'order_id')
			->orderBy('created_at', 'DESC');
	}

	public function children() {
		return $this->hasMany('sisVentas\Order', 'parent_order_id');
	}

	public function parent() {
		return $this->belongsTo('sisVentas\Order', 'parent_order_id');
	}

	public function order_type() {
		return $this->belongsTo('sisVentas\OrderType', 'order_type_id');
	}

	//$order = Order::with('details')->find(1);
	//foreach order->details as key => detail 

}
