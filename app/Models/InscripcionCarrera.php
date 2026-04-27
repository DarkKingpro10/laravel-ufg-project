<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscripcionCarrera extends Model
{
    protected $table = 'alumno_carrera';

    protected $fillable = [
        'alumno_id',
        'carrera_id'
    ];
}
