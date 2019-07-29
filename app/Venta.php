<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;
use Optica\DetalleVenta;

class Venta extends Model
{
  protected $table = 'ventas';

    protected $fillable = [
        'idcliente',
        'idusuario',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total',
        'estado',

        'esfera','cilindro','eje','add','dip','av','prisma',
        'esfera2','cilindro2','eje2','av2','prisma2',

        'idproveedor','referencia',

        'puente','hor','vert','diag',

        'color','efecto','tono',

        'angulo_pantoscopio','ang_curvatura','dist_lectura','st','he',

        'adelanto','pediente','forma_pago',

        'adelanto_v',

        'idsucursal',

        'material',
        'cmaterial'

    ];
    public function detalle_ventas()
    {
      return $this->hasMany('Optica\DetalleVenta', 'idventa');
    }
    public function persona()
    {
      return $this->belongsTo('Optica\Persona');
    }

}
