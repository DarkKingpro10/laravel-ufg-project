@extends('layout')

@section('content')
<h1>Lista de Alumnos</h1>
<a href="{{ route('alumnos.create') }}" class="btn btn-primary mb-3">Agregar Alumno</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>NIE</th>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Responsable</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($alumnos as $alumno)
        <tr>
            <td>{{ $alumno->id }}</td>
            <td>{{ $alumno->nie }}</td>
            <td>{{ $alumno->nombres }} {{ $alumno->apellidos }}</td>
            <td>{{ $alumno->edad }}</td>
            <td>{{ $alumno->sexo }}</td>
            <td>{{ $alumno->direccion }}</td>
            <td>{{ $alumno->telefono }}</td>
            <td>{{ $alumno->email }}</td>
            <td>{{ $alumno->responsable }}</td>
            <td>

                <a href="{{ route('alumnos.edit', $alumno->id) }}" class="btn btn-warning">Editar</a>
                <form action="{{ route('alumnos.destroy', $alumno->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que desea eliminar este alumno?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection