<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion'
    ];

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'alumno_carrera')->withTimestamps();
    }
}
