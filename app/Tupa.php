<?php
namespace sisVentas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tupa extends Model {
	use SoftDeletes;
	
	protected $table = 'tupa';

	protected $primaryKey = 'id';

	public $timestamps = true;
	
	protected $fillable = [
		'title',
		'order',
		'email',
		'cellphone',
		'phone',
	];

	protected $guarded = [

	];

	public function requirements() {
		return $this->hasMany('sisVentas\TupaRequirement', 'tupa_id');
	}
}
