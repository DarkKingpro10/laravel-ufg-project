<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\InscripcionMateria;
use App\Models\Ciclo;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotaController extends Controller
{
    public function index()
    {
        $materiaId = request()->get('materia_id');
        $cicloId = request()->get('ciclo_id');
        // Si se filtra por materia/ciclo, mostrar los alumnos inscritos aunque no tengan fila en 'notas'
        if ($materiaId || $cicloId) {
            $insQuery = InscripcionMateria::with(['alumno', 'horario.materia', 'horario.docente', 'ciclo']);

            if ($materiaId) {
                $insQuery->whereHas('horario', function ($q) use ($materiaId) {
                    $q->where('materia_id', $materiaId);
                });
            }
            if ($cicloId) {
                $insQuery->where('ciclo_id', $cicloId);
            }

            $inscripciones = $insQuery->get();

            // asegurarse de que existan registros en notas para cada inscripcion
            $notas = collect();
            foreach ($inscripciones as $ins) {
                $nota = Nota::firstOrCreate(['inscripcion_id' => $ins->id]);
                // refrescar relaciones
                $nota->load('inscripcion.alumno', 'inscripcion.horario.materia', 'inscripcion.horario.docente', 'inscripcion.ciclo');
                $notas->push($nota);
            }
        } else {
            $query = Nota::with(['inscripcion.alumno', 'inscripcion.horario.materia', 'inscripcion.horario.docente', 'inscripcion.ciclo']);
            $notas = $query->get();
        }
        $materias = Materia::orderBy('nombre')->get();
        $ciclos = Ciclo::orderBy('activo', 'desc')->get();

        return view('notas.index', compact('notas', 'materias', 'ciclos', 'materiaId', 'cicloId'));
    }

    // show edit modal/page for a single nota (inscripcion)
    public function edit($id)
    {
        $nota = Nota::with('inscripcion.alumno', 'inscripcion.horario.materia')->findOrFail($id);
        return view('notas.edit', compact('nota'));
    }

    public function update(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        $data = $request->validate([
            'lab_p1' => 'nullable|numeric|min:0|max:10',
            'parc_p1' => 'nullable|numeric|min:0|max:10',
            'lab_p2' => 'nullable|numeric|min:0|max:10',
            'parc_p2' => 'nullable|numeric|min:0|max:10',
            'lab_p3' => 'nullable|numeric|min:0|max:10',
            'parc_p3' => 'nullable|numeric|min:0|max:10',
            'lab_p4' => 'nullable|numeric|min:0|max:10',
            'parc_p4' => 'nullable|numeric|min:0|max:10',
        ]);

        $nota->update($data);
        return redirect()->route('notas.index')->with('success', 'Nota actualizada');
    }

    // manage notes for a student: show all inscriptions and notes for a given alumno and ciclo, allow bulk edit
    public function manageAlumno($alumnoId)
    {
        $ciclos = Ciclo::orderBy('activo', 'desc')->get();
        $cicloId = request()->get('ciclo_id') ? request()->get('ciclo_id') : ($ciclos->first()->id ?? null);

        $inscripciones = InscripcionMateria::with(['alumno', 'horario.materia', 'horario.docente'])
            ->where('alumno_id', $alumnoId)
            ->where('ciclo_id', $cicloId)
            ->get();

        // ensure Nota entries exist for each inscripcion
        foreach ($inscripciones as $ins) {
            Nota::firstOrCreate(['inscripcion_id' => $ins->id]);
        }

        $notas = Nota::with('inscripcion.horario.materia')->whereHas('inscripcion', function ($q) use ($alumnoId, $cicloId) {
            $q->where('alumno_id', $alumnoId)->where('ciclo_id', $cicloId);
        })->get();

        $alumno = $inscripciones->first()->alumno ?? \App\Models\Alumno::find($alumnoId);

        return view('notas.manage', compact('notas', 'ciclos', 'cicloId', 'alumno'));
    }

    public function storeAlumno(Request $request)
    {
        $data = $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'ciclo_id' => 'required|exists:ciclos,id',
            'notas' => 'nullable|array'
        ]);

        $notas = $data['notas'] ?? [];

        DB::transaction(function () use ($notas) {
            $allowed = ['lab_p1', 'parc_p1', 'lab_p2', 'parc_p2', 'lab_p3', 'parc_p3', 'lab_p4', 'parc_p4'];
            foreach ($notas as $id => $vals) {
                $nota = Nota::find($id);
                if (!$nota) continue;
                $clean = [];
                foreach ($allowed as $f) {
                    if (array_key_exists($f, $vals)) {
                        $v = $vals[$f];
                        if ($v === '' || $v === null) {
                            $clean[$f] = null;
                            continue;
                        }
                        if (!is_numeric($v)) {
                            // ignore non-numeric
                            continue;
                        }
                        $num = floatval($v);
                        // clamp between 0 and 10
                        if ($num < 0) $num = 0;
                        if ($num > 10) $num = 10;
                        $clean[$f] = $num;
                    }
                }
                if (!empty($clean)) {
                    $nota->fill($clean);
                    $nota->save();
                }
            }
        });

        return back()->with('success', 'Notas actualizadas');
    }

    // Populate notas table with default records for all existing inscripciones
    public function populate(Request $request)
    {
        $count = 0;
        $inscripciones = InscripcionMateria::with('alumno')->get();
        foreach ($inscripciones as $ins) {
            $nota = Nota::firstOrCreate(['inscripcion_id' => $ins->id]);
            if ($nota->wasRecentlyCreated) $count++;
        }

        return back()->with('success', "Se han creado {$count} registros de notas (si faltaban).");
    }
}
