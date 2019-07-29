<?php

namespace Optica;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'usuario', 'password', 'condicion', 'idrol', 'idsucursal'
    ];

    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function rol(){
        return $this->belongsTo('Optica\Rol');
    }

    public function persona(){
        return $this->belongsTo('Optica\Persona');
    }

    public function sucursal(){
        return $this->belongsTo('Optica\Sucursal');
    }
}
