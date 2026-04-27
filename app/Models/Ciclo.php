<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciclo extends Model
{
    protected $fillable = ['nombre', 'activo', 'fecha_inicio', 'fecha_fin'];
}
