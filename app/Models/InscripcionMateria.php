<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscripcionMateria extends Model
{
    protected $table = 'alumno_materia';

    protected $fillable = [
        'alumno_id',
        'horario_id',
        'ciclo_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class);
    }
}
