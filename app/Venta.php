<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;
use sisVentas\Persona;

class Venta extends Model {
	protected $table = 'venta';

	protected $primaryKey = 'idventa';

	public $timestamps = false;

	protected $fillable = [
		'idcliente',
		'tipo_comprobante',
		'serie_comprobante',
		'num_comprobante',
		'fecha_hora',
		'impuesto',
		'total_venta',
		'estado',
	];
	protected $guarded = [
	];

	public function persona() {
		return $this->belongsTo(Persona::class, 'idcliente');
	}

	public function products() {
		return $this->belongsToMany('sisVentas\Articulo', 'detalle_venta', 'idventa', 'idarticulo')->withPivot('iddetalle_venta', 'idventa', 'idarticulo', 'cantidad', 'precio_venta');
	}

}
