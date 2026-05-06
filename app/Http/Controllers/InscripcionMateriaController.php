<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Horario;
use App\Models\Ciclo;
use App\Models\InscripcionMateria;
use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscripcionMateriaController extends Controller
{
    // List all inscriptions with details
    public function index()
    {
        $materiaId = request()->get('materia_id');
        $cicloId = request()->get('ciclo_id');

        $query = InscripcionMateria::with(['alumno', 'horario.materia', 'horario.docente', 'ciclo']);

        if ($materiaId) {
            $query->whereHas('horario', function ($q) use ($materiaId) {
                $q->where('materia_id', $materiaId);
            });
        }

        if ($cicloId) {
            $query->where('ciclo_id', $cicloId);
        }

        $inscripciones = $query->orderBy('created_at', 'desc')->get();

        // datos para filtros
        $materias = \App\Models\Materia::orderBy('nombre')->get();
        $ciclos = Ciclo::orderBy('activo', 'desc')->get();

        return view('inscripcion_materia.index', compact('inscripciones', 'materias', 'ciclos', 'materiaId', 'cicloId'));
    }

    // Mostrar y administrar inscripciones de un alumno por ciclo (bulk)
    public function manageAlumno($alumnoId)
    {
        $alumno = Alumno::findOrFail($alumnoId);
        $ciclos = Ciclo::orderBy('activo', 'desc')->get();

        $cicloId = request()->get('ciclo_id') ? request()->get('ciclo_id') : ($ciclos->first()->id ?? null);

        $horarios = Horario::with(['materia', 'docente'])->orderBy('dia')->orderBy('hora_inicio')->get();

        // horarios seleccionados actualmente por el alumno en el ciclo
        $selected = InscripcionMateria::where('alumno_id', $alumnoId)
            ->where('ciclo_id', $cicloId)
            ->pluck('horario_id')
            ->toArray();

        return view('inscripcion_materia.manage', compact('alumno', 'ciclos', 'cicloId', 'horarios', 'selected'));
    }

    // Guardar inscripciones en bloque para un alumno y ciclo (reemplaza las existentes)
    public function storeAlumno(Request $request)
    {
        $data = $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'ciclo_id' => 'required|exists:ciclos,id',
            'horario_ids' => 'nullable|array'
        ]);

        $alumnoId = $data['alumno_id'];
        $cicloId = $data['ciclo_id'];
        $horarioIds = isset($data['horario_ids']) ? array_map('intval', $data['horario_ids']) : [];

        // validar límite de materias (distintas)
        $materiasSeleccionadas = Horario::whereIn('id', $horarioIds)->pluck('materia_id')->unique()->count();

        if ($materiasSeleccionadas > 5) {
            return back()->withInput()->with('error', 'No se pueden inscribir más de 5 materias.');
        }

        // validar choques entre horarios seleccionados
        $nuevos = Horario::whereIn('id', $horarioIds)->get();
        for ($i = 0; $i < $nuevos->count(); $i++) {
            for ($j = $i + 1; $j < $nuevos->count(); $j++) {
                $a = $nuevos[$i];
                $b = $nuevos[$j];
                if ($a->dia === $b->dia) {
                    if ($a->hora_inicio < $b->hora_fin && $a->hora_fin > $b->hora_inicio) {
                        return back()->withInput()->with('error', 'Choque entre horarios seleccionados.');
                    }
                }
            }
        }

        DB::transaction(function () use ($alumnoId, $cicloId, $horarioIds) {
            // eliminar actuales para ese alumno/ciclo
            InscripcionMateria::where('alumno_id', $alumnoId)->where('ciclo_id', $cicloId)->delete();
            foreach ($horarioIds as $hid) {
                $ins = InscripcionMateria::create([
                    'alumno_id' => $alumnoId,
                    'horario_id' => $hid,
                    'ciclo_id' => $cicloId
                ]);
                // crear fila de notas con valores por defecto (0)
                Nota::firstOrCreate(
                    ['inscripcion_id' => $ins->id],
                    [
                        'lab_p1' => 0,
                        'parc_p1' => 0,
                        'lab_p2' => 0,
                        'parc_p2' => 0,
                        'lab_p3' => 0,
                        'parc_p3' => 0,
                        'lab_p4' => 0,
                        'parc_p4' => 0
                    ]
                );
            }
        });

        return redirect()->route('inscripcion-materia.manageAlumno', ['alumno' => $alumnoId, 'ciclo_id' => $cicloId])->with('success', 'Inscripciones actualizadas');
    }

    public function create()
    {
        $alumnos = Alumno::orderBy('nombres')->get();
        $horarios = Horario::with(['materia', 'docente'])->orderBy('dia')->orderBy('hora_inicio')->get();
        $ciclos = Ciclo::orderBy('activo', 'desc')->get();

        return view('inscripcion_materia.create', compact('alumnos', 'horarios', 'ciclos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'ciclo_id' => 'required|exists:ciclos,id',
            'horario_ids' => 'required|array|min:1'
        ]);

        $alumnoId = $data['alumno_id'];
        $cicloId = $data['ciclo_id'];
        $horarioIds = array_map('intval', $data['horario_ids']);

        // obtener materias para los horarios seleccionados
        $materiasNuevas = Horario::whereIn('id', $horarioIds)->pluck('materia_id')->unique()->values();

        // contar materias distintas ya inscritas por alumno en ese ciclo
        $materiasActuales = InscripcionMateria::where('alumno_id', $alumnoId)
            ->where('ciclo_id', $cicloId)
            ->join('horarios', 'alumno_materia.horario_id', '=', 'horarios.id')
            ->distinct()
            ->pluck('horarios.materia_id')
            ->toArray();

        $distinctNuevas = $materiasNuevas->filter(function ($m) use ($materiasActuales) {
            return !in_array($m, $materiasActuales);
        })->unique()->count();

        if (count($materiasActuales) + $distinctNuevas > 5) {
            return back()->withInput()->with('error', 'El alumno excedería el límite de 5 materias en este ciclo.');
        }

        // Validar choques de horario: para cada horario nuevo, compararlo con horarios existentes del alumno en el mismo ciclo
        $horariosExistentes = InscripcionMateria::where('alumno_id', $alumnoId)
            ->where('ciclo_id', $cicloId)
            ->pluck('horario_id')
            ->toArray();

        $existHorarios = Horario::whereIn('id', $horariosExistentes)->get();
        $nuevosHorarios = Horario::whereIn('id', $horarioIds)->get();

        foreach ($nuevosHorarios as $nh) {
            foreach ($existHorarios as $eh) {
                if ($nh->dia === $eh->dia) {
                    // solapamiento: start < other_end && end > other_start
                    if ($nh->hora_inicio < $eh->hora_fin && $nh->hora_fin > $eh->hora_inicio) {
                        return back()->withInput()->with('error', 'Choque de horario detectado con horario existente.');
                    }
                }
            }
        }

        // insertar en transacción
        DB::transaction(function () use ($alumnoId, $cicloId, $horarioIds) {
            foreach ($horarioIds as $hid) {
                $ins = InscripcionMateria::firstOrCreate([
                    'alumno_id' => $alumnoId,
                    'horario_id' => $hid,
                    'ciclo_id' => $cicloId
                ]);
                // ensure nota exists with zeros
                Nota::firstOrCreate(
                    ['inscripcion_id' => $ins->id],
                    [
                        'lab_p1' => 0,
                        'parc_p1' => 0,
                        'lab_p2' => 0,
                        'parc_p2' => 0,
                        'lab_p3' => 0,
                        'parc_p3' => 0,
                        'lab_p4' => 0,
                        'parc_p4' => 0
                    ]
                );
            }
        });

        return redirect()->route('inscripcion-materia.index')->with('success', 'Inscripciones creadas');
    }

    public function edit($id)
    {
        $ins = InscripcionMateria::findOrFail($id);
        $alumnos = Alumno::orderBy('nombres')->get();
        $horarios = Horario::with(['materia', 'docente'])->orderBy('dia')->orderBy('hora_inicio')->get();
        $ciclos = Ciclo::orderBy('activo', 'desc')->get();

        return view('inscripcion_materia.edit', compact('ins', 'alumnos', 'horarios', 'ciclos'));
    }

    public function update(Request $request, $id)
    {
        $ins = InscripcionMateria::findOrFail($id);

        $data = $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'ciclo_id' => 'required|exists:ciclos,id',
            'horario_id' => 'required|exists:horarios,id'
        ]);

        $ins->alumno_id = $data['alumno_id'];
        $ins->ciclo_id = $data['ciclo_id'];
        $ins->horario_id = $data['horario_id'];

        // validar choques contra otros inscripciones del alumno en el mismo ciclo (excluir la actual)
        $horarioNuevo = Horario::find($data['horario_id']);
        $otros = InscripcionMateria::where('alumno_id', $data['alumno_id'])
            ->where('ciclo_id', $data['ciclo_id'])
            ->where('id', '!=', $ins->id)
            ->pluck('horario_id')
            ->toArray();
        $otrosHor = Horario::whereIn('id', $otros)->get();
        foreach ($otrosHor as $oh) {
            if ($horarioNuevo->dia === $oh->dia) {
                if ($horarioNuevo->hora_inicio < $oh->hora_fin && $horarioNuevo->hora_fin > $oh->hora_inicio) {
                    return back()->withInput()->with('error', 'Choque de horario detectado al actualizar.');
                }
            }
        }

        $ins->save();
        return redirect()->route('inscripcion-materia.index')->with('success', 'Inscripción actualizada');
    }

    public function destroy($id)
    {
        $ins = InscripcionMateria::find($id);
        if ($ins) $ins->delete();
        return back()->with('success', 'Inscripción eliminada');
    }
}
