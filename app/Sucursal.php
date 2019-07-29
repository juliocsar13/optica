<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';
    protected $fillable = [
        'razon_social_s',
        'tipo_documento_s',
        'num_documento_s',
        'direccion_s',
        'telefono_s',
        'email_s',
        'representante_s'
    ];

    public function users()
    {
        return $this->hasMany('Optica\User');
    }
    public function productos()
    {
        return $this->hasMany('Optica\Productos');
    }
}
