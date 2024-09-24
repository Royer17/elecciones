<?php
namespace sisVentas;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model {
	protected $table = 'articulo';

	protected $primaryKey = 'idarticulo';

	public $timestamps = false;

	protected $fillable = [
		'idcategoria',
		'codigo',
		'nombre',
		'price',
		'stock',
		'descripcion',
		'imagen',
		'estado',
	];

	protected $guarded = [

	];

	public function orders() {
		return $this->belongsToMany('sisVentas\Venta', 'detalle_venta', 'idarticulo', 'idventa')->withPivot('iddetalle_venta', 'idventa', 'idarticulo', 'cantidad', 'precio_venta');
	}

	public function category() {
		return $this->belongsTo('sisVentas\Categoria', 'idcategoria');
	}
}
