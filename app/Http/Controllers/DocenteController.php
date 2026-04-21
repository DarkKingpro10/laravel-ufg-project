<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function index()
    {
        $docentes = Docente::all();
        return view('docentes.index', compact('docentes'));
    }

    public function create()
    {
        return view('docentes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nombre' => 'required|string|max:255']);
        Docente::create($data);
        return redirect()->route('docentes.index')->with('success', 'Docente creado.');
    }

    public function edit($id)
    {
        $docente = Docente::findOrFail($id);
        return view('docentes.edit', compact('docente'));
    }

    public function update(Request $request, $id)
    {
        $docente = Docente::findOrFail($id);
        $data = $request->validate(['nombre' => 'required|string|max:255']);
        $docente->update($data);
        return redirect()->route('docentes.index')->with('success', 'Docente actualizado.');
    }

    public function destroy($id)
    {
        Docente::destroy($id);
        return redirect()->route('docentes.index')->with('success', 'Docente eliminado.');
    }
}
