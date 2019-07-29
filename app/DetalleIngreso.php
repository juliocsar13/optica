<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $table = 'detalle_ingresos';
    protected $fillable = [
        'idingreso',
        'idproducto',
        'cantidad',
        'precio'
    ];
    //public $timestamps = false;

}
