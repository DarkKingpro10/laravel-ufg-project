@extends('layout')

@section('content')
<div class="container">
    <h1>Carreras</h1>
    <a href="{{ route('carreras.create') }}" class="btn btn-primary mb-3">Nueva Carrera</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="carreras-table" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carreras as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->codigo }}</td>
                <td>{{ $c->nombre }}</td>
                <td>{{ $c->descripcion }}</td>
                <td>
                    <a href="{{ route('carreras.edit', $c) }}" class="btn btn-sm btn-secondary">Editar</a>
                    <form action="{{ route('carreras.destroy', $c) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar?')">Eliminar</button>
                    </form>
                    <a href="{{ route('inscripciones.index', ['carrera_id'=>$c->id]) }}" class="btn btn-sm btn-info">Ver alumnos</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    $(function() {
        $('#carreras-table').DataTable({
            responsive: true
        });
    });
</script>
@endsection