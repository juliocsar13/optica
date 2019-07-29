<?php

namespace Optica;

use Illuminate\Database\Eloquent\Model;

class Graduacion extends Model
{
    protected $table = 'graduaciones';
    protected $fillable = ['nombre','valor','condicion'];
}
