<?php
namespace sisVentas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TupaRequirement extends Model {
	use SoftDeletes;
	
	protected $table = 'tupa_requirements';

	protected $primaryKey = 'id';
	
	public $timestamps = true;

	protected $fillable = [
		'name',
		'description',
		'link',
		'link_path',
		'tupa_id',
	];

	protected $guarded = [

	];

	public function tupa() {
		return $this->belongsTo('sisVentas\Tupa', 'tupa_id');
	}
}
