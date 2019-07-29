<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['nombre','tipo_documento','num_documento','direccion','telefono','email'];

    public function proveedor()
    {
        return $this->hasOne('Optica\Proveedor');
    }

    public function user()
    {
        return $this->hasOne('Optica\User');
    }
    public function venta()
    {
        return $this->belongsTo('Optica\Venta');
    }
}
