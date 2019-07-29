<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';
    protected $fillable = [
        'idventa',
        'idproducto',
        'cantidad',
        'precio',
        'descuento',
        'n_material'
    ];
    //public $timestamps = false;
    public function venta(){
        return $this->belongsTo('Optica\Venta', 'id');
    }
    public function productos(){
        return $this->hasMany('Optica\Producto','id');
    }

}
