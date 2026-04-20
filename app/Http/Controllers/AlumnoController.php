<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alumnos = Alumno::all();
        return view('alumnos.index', compact('alumnos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('alumnos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nie' => 'required|string|unique:alumnos,nie',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'edad' => 'required|integer|min:1',
            'sexo' => 'nullable|in:M,F',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:alumnos,email',
            'responsable' => 'nullable|string|max:255',
        ]);

        Alumno::create($data);

        return redirect()->route('alumnos.index')->with('success', 'Alumno creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumno $alumno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $alumno = Alumno::findOrFail($id);
        return view('alumnos.edit', compact('alumno'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $alumno = Alumno::findOrFail($id);
        $data = $request->validate([
            'nie' => 'required|string|unique:alumnos,nie,' . $id,
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'edad' => 'required|integer|min:1',
            'sexo' => 'nullable|in:M,F',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:alumnos,email,' . $id,
            'responsable' => 'nullable|string|max:255',
        ]);

        $alumno->update($data);

        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Alumno::destroy($id);
        return redirect()->route('alumnos.index');
    }
}
