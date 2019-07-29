<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
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

        'adelanto','pediente','forma_pago'

    ];
}
