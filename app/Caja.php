<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'cajas';

    protected $fillable = [
        'idpersona',
        'descripcion',
        'monto_inicial',
        'monto_final',
        'estado',
        'fecha_apertura',
        'fecha_cierre',
    ];
}
