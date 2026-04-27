<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Alumno;
use App\Models\InscripcionCarrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
{
    public function index()
    {
        $carreras = Carrera::orderBy('nombre')->get();
        return view('carreras.index', compact('carreras'));
    }

    public function create()
    {
        return view('carreras.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|unique:carreras,codigo',
            'nombre' => 'required',
            'descripcion' => 'nullable'
        ]);
        Carrera::create($data);
        return redirect()->route('carreras.index')->with('success', 'Carrera creada');
    }

    public function edit(Carrera $carrera)
    {
        return view('carreras.edit', compact('carrera'));
    }

    public function update(Request $request, Carrera $carrera)
    {
        $data = $request->validate([
            'codigo' => 'required|unique:carreras,codigo,' . $carrera->id,
            'nombre' => 'required',
            'descripcion' => 'nullable'
        ]);
        $carrera->update($data);
        return redirect()->route('carreras.index')->with('success', 'Carrera actualizada');
    }

    public function destroy(Carrera $carrera)
    {
        $carrera->delete();
        return redirect()->route('carreras.index')->with('success', 'Carrera eliminada');
    }

    // Mostrar inscripciones y listado de alumnos filtrado por carrera
    public function inscripciones(Request $request)
    {
        $carreras = Carrera::orderBy('nombre')->get();
        $selected = $request->get('carrera_id');
        $alumnos = Alumno::query();

        if ($selected) {
            $alumnos = $alumnos->whereHas('carreras', function ($q) use ($selected) {
                $q->where('carreras.id', $selected);
            });
        }

        $alumnos = $alumnos->orderBy('nombres')->get();

        return view('carreras.inscripciones', compact('carreras', 'alumnos', 'selected'));
    }

    public function storeInscripcion(Request $request)
    {
        $data = $request->validate([
            'alumno_id' => 'required|exists:alumnos,id',
            'carrera_id' => 'required|exists:carreras,id',
        ]);

        InscripcionCarrera::firstOrCreate($data);
        return back()->with('success', 'Inscripción realizada');
    }

    public function destroyInscripcion($id)
    {
        $ins = InscripcionCarrera::find($id);
        if ($ins) $ins->delete();
        return back()->with('success', 'Inscripción eliminada');
    }
}
