<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
  protected $table = 'movimientos';

    protected $fillable = [
      'idpersona',
      'descripcion',
      'monto',
      'tipo',
      'estado',
      'idsucursal'  
    ];
}
