<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $fillable = [
        'nie',
        'nombres',
        'apellidos',
        'edad',
        'sexo',
        'direccion',
        'telefono',
        'email',
        'responsable'
    ];

    public function carreras()
    {
        return $this->belongsToMany(Carrera::class, 'alumno_carrera')->withTimestamps();
    }
}
