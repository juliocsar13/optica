<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'idfamilia','codigo','nombre','precio_venta','stock','descripcion','condicion','idsucursal'
    ];
    public function familia(){
        return $this->belongsTo('Optica\Familia');
    }

    public function sucursal(){
        return $this->belongsTo('Optica\Sucursal');
    }

    public function detalle_ventas()
    {
      return $this->hasMany('Optica\DetalleVenta', 'idproducto');
    }

    public function detalle_ingresos()
    {
      return $this->hasMany('Optica\DetalleIngreso', 'idproducto');
    }
}
