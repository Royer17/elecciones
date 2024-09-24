<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;
use sisVentas\DetalleIngreso;
use sisVentas\Persona;

class Ingreso extends Model
{
    protected $table='ingreso';

    protected $primaryKey='idingreso';

    public $timestamps=false;

    protected $fillable =[
    	'idproveedor',
    	'tipo_comprobante',
    	'serie_comprobante',
    	'num_comprobante',
    	'fecha_hora',
    	'impuesto',
    	'estado'
    ];
    protected $guarded =[
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'idproveedor');
    }

    public function detalle_ingreso()
    {
        return $this->hasMany(DetalleIngreso::class, 'idingreso');
    }
}
