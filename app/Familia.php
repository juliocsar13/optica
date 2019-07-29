<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    //protected $table = 'familias';
    //protected $primaryKey = 'id';
    protected $fillable = ['nombre', 'descripcion', 'condicion'];

    public function productos()
    {
        return $this->hasMany('Optica\Producto');
    }
}
