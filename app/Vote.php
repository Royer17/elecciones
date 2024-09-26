<?php
namespace sisVentas;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model {
	protected $table = 'votes';

	protected $primaryKey = 'id';

	protected $fillable = [
		'nivel',
		'grade',
		'section',
		'category_candidate_id',
		'candidate_id',
	];

	protected $guarded = [

	];

}
