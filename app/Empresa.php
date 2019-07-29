<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        'direccion_e',
        'email_e',
        'nombre_e',
        'razon_social',
        'representante',
        'ruc_e',
        'telefono_e'
    ];
}
