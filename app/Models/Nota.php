<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $fillable = [
        'inscripcion_id',
        'lab_p1',
        'parc_p1',
        'lab_p2',
        'parc_p2',
        'lab_p3',
        'parc_p3',
        'lab_p4',
        'parc_p4'
    ];

    public function inscripcion()
    {
        return $this->belongsTo(InscripcionMateria::class, 'inscripcion_id');
    }

    // calcular promedio según pesos: labs 10% cada uno, parciales 15% cada uno
    public function promedio()
    {
        $sumLab = 0;
        $sumPar = 0;
        for ($p = 1; $p <= 4; $p++) {
            $lab = $this->{"lab_p{$p}"} ?? 0;
            $par = $this->{"parc_p{$p}"} ?? 0;
            $sumLab += $lab;
            $sumPar += $par;
        }
        // total = sumLab*0.10 + sumPar*0.15
        return round($sumLab * 0.10 + $sumPar * 0.15, 2);
    }
}
