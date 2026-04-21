<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Docente;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    public function index(Request $request)
    {
        $docentes = Docente::all();
        $query = Horario::with(['docente', 'materia'])->orderBy('dia')->orderBy('hora_inicio');
        if ($request->filled('docente_id')) {
            $query->where('docente_id', $request->docente_id);
        }
        $horarios = $query->get();
        return view('horarios.index', compact('horarios', 'docentes'));
    }

    public function inscribirForm()
    {
        $docentes = Docente::all();
        $materias = Materia::all();
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return view('horarios.inscribir', compact('docentes', 'materias', 'dias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'docente_id' => 'required|exists:docentes,id',
            'entries' => 'required|array|min:1|max:5',
            'entries.*.materia_id' => 'required|exists:materias,id',
            'entries.*.dia' => 'required|string',
            'entries.*.hora_inicio' => 'required|date_format:H:i',
            'entries.*.hora_fin' => 'required|date_format:H:i|after:entries.*.hora_inicio',
            'entries.*.aula' => 'nullable|string|max:50',
        ]);

        $docente = Docente::findOrFail($request->docente_id);

        $existingCount = Horario::where('docente_id', $docente->id)->count();
        if ($existingCount + count($request->entries) > 5) {
            return back()->withErrors(['entries' => 'Máximo 5 materias por docente.'])->withInput();
        }

        DB::beginTransaction();
        try {
            foreach ($request->entries as $entry) {
                // conflict global: no solapamiento en mismo dia y franja
                $conflict = Horario::where('dia', $entry['dia'])
                    ->where(function ($q) use ($entry) {
                        $q->where(function ($q2) use ($entry) {
                            $q2->where('hora_inicio', '<', $entry['hora_fin'])
                                ->where('hora_fin', '>', $entry['hora_inicio']);
                        });
                    })->exists();

                if ($conflict) {
                    DB::rollBack();
                    return back()->withErrors(['conflict' => 'Conflicto de horario detectado.'])->withInput();
                }

                Horario::create([
                    'docente_id' => $docente->id,
                    'materia_id' => $entry['materia_id'],
                    'dia' => $entry['dia'],
                    'hora_inicio' => $entry['hora_inicio'],
                    'hora_fin' => $entry['hora_fin'],
                    'aula' => $entry['aula'] ?? null,
                ]);
            }
            DB::commit();
            return redirect()->route('horarios.index')->with('success', 'Inscripción(s) guardada(s).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al guardar inscripciones.'])->withInput();
        }
    }

    public function destroy($id)
    {
        Horario::destroy($id);
        return redirect()->route('horarios.index')->with('success', 'Horario eliminado.');
    }

    public function edit($id)
    {
        $horario = Horario::findOrFail($id);
        $docentes = Docente::all();
        $materias = Materia::all();
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return view('horarios.edit', compact('horario', 'docentes', 'materias', 'dias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'docente_id' => 'required|exists:docentes,id',
            'materia_id' => 'required|exists:materias,id',
            'dia' => 'required|string',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'aula' => 'nullable|string|max:50',
        ]);

        $horario = Horario::findOrFail($id);

        // Si cambiamos de docente, validar límite de 5
        if ($horario->docente_id != $request->docente_id) {
            $count = Horario::where('docente_id', $request->docente_id)->count();
            if ($count >= 5) {
                return back()->withErrors(['docente_id' => 'El docente seleccionado ya tiene 5 materias.'])->withInput();
            }
        }

        // Validar conflicto global (excluir el propio registro)
        $conflict = Horario::where('dia', $request->dia)
            ->where('id', '<>', $horario->id)
            ->where(function ($q) use ($request) {
                $q->where('hora_inicio', '<', $request->hora_fin)
                    ->where('hora_fin', '>', $request->hora_inicio);
            })->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'Conflicto de horario detectado.'])->withInput();
        }

        $horario->docente_id = $request->docente_id;
        $horario->materia_id = $request->materia_id;
        $horario->dia = $request->dia;
        $horario->hora_inicio = $request->hora_inicio;
        $horario->hora_fin = $request->hora_fin;
        $horario->aula = $request->aula;
        $horario->save();

        return redirect()->route('horarios.index')->with('success', 'Horario actualizado.');
    }
}
