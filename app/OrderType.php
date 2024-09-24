<?php
namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class OrderType extends Model {
	protected $table = 'order_types';

	protected $primaryKey = 'id';

	public $timestamps = true;

	protected $fillable = [
		'name',
	];

	protected $guarded = [

	];

	public function orders() {
		return $this->hasMany('sisVentas\Order', 'order_type_id');
	}
}
