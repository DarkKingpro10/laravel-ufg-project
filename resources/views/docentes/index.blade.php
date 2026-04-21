@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Docentes</h1>
    <a href="{{ route('docentes.create') }}" class="btn btn-primary">Crear docente</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="table-responsive">
    <table id="docentes_table" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($docentes as $d)
            <tr>
                <td>{{ $d->id }}</td>
                <td>{{ $d->nombre }}</td>
                <td>
                    <a href="{{ route('docentes.edit',$d->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                    <form action="{{ route('docentes.destroy',$d->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Eliminar?')">
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
        new DataTable('#docentes_table', {
            responsive: true
        });
    });
</script>
@endsection