<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $fillable = [
        'idproveedor',
        'idusuario',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total',
        'estado',
        'adelantoI',
        'pendienteI',
        'forma_pagoI',
        'idsucursal'
    ];
    public function usuario()
    {
        return $this->belongsTo('Optica\User');
    }
    public function proveedor()
    {
        return $this->belongsTo('Optica\Proveedor');
    }
}