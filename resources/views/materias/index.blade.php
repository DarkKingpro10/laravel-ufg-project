@extends('layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Materias</h1>
    <a href="{{ route('materias.create') }}" class="btn btn-primary">Crear materia</a>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="table-responsive">
    <table id="materias_table" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Código</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materias as $m)
            <tr>
                <td>{{ $m->id }}</td>
                <td>{{ $m->nombre }}</td>
                <td>{{ $m->codigo }}</td>
                <td>
                    <a href="{{ route('materias.edit',$m->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                    <form action="{{ route('materias.destroy',$m->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#materias_table', {
            responsive: true
        });
    });
</script>
@endsection