<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::all();
        return view('materias.index', compact('materias'));
    }

    public function create()
    {
        return view('materias.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nombre' => 'required|string|max:255', 'codigo' => 'nullable|string|max:50']);
        Materia::create($data);
        return redirect()->route('materias.index')->with('success', 'Materia creada.');
    }

    public function edit($id)
    {
        $materia = Materia::findOrFail($id);
        return view('materias.edit', compact('materia'));
    }

    public function update(Request $request, $id)
    {
        $materia = Materia::findOrFail($id);
        $data = $request->validate(['nombre' => 'required|string|max:255', 'codigo' => 'nullable|string|max:50']);
        $materia->update($data);
        return redirect()->route('materias.index')->with('success', 'Materia actualizada.');
    }

    public function destroy($id)
    {
        Materia::destroy($id);
        return redirect()->route('materias.index')->with('success', 'Materia eliminada.');
    }
}
