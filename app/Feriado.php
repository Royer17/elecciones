<?php
namespace sisVentas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feriado extends Model {

	use SoftDeletes;

	protected $table = 'feriados';

	protected $primaryKey = 'id';

	public $timestamps = true;

	protected $fillable = [
		'description', 'date', 'anual', 'month_day', 'date_string'
	];

	protected $guarded = [

	];

}
