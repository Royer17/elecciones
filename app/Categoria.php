<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model {
	protected $table = 'categoria';

	protected $primaryKey = 'idcategoria';

	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'slug',
		'descripcion',
		'condicion',
		'outstanding',
	];

	protected $guarded = [

	];

	public function products() {
		return $this->hasMany('sisVentas\Articulo', 'idcategoria');
	}

	public function products_actived() {
		return $this->hasMany('sisVentas\Articulo', 'idcategoria')->whereEstado('Activo');
	}

}
