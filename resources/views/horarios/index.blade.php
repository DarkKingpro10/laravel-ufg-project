@extends('layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Horarios / Inscripciones</h1>
    <a href="{{ route('horarios.inscribir') }}" class="btn btn-primary">Inscribir</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="mb-3">
    <label>Filtrar por docente</label>
    <select id="filter_docente" class="form-select" style="max-width:300px;">
        <option value="">Todos</option>
        @foreach($docentes as $d)
        <option value="{{ $d->id }}">{{ $d->nombre }}</option>
        @endforeach
    </select>
</div>

<div class="table-responsive">
    <table id="horarios_table" class="table table-striped text-center align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Docente</th>
                <th>Materia</th>
                <th>Día</th>
                <th>Hora inicio</th>
                <th>Hora fin</th>
                <th>Aula</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($horarios as $h)
            <tr data-docente="{{ $h->docente_id }}">
                <td>{{ $h->id }}</td>
                <td>{{ $h->docente->nombre }}</td>
                <td>{{ $h->materia->nombre }}</td>
                <td>{{ $h->dia }}</td>
                <td>{{ $h->hora_inicio }}</td>
                <td>{{ $h->hora_fin }}</td>
                <td>{{ $h->aula }}</td>
                <td>
                    <form action="{{ route('horarios.destroy',$h->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Eliminar?')">
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
        const dt = new DataTable('#horarios_table', {
            responsive: true
        });
        const filter = document.getElementById('filter_docente');
        filter.addEventListener('change', function() {
            const val = this.value;
            document.querySelectorAll('#horarios_table tbody tr').forEach(r => {
                r.style.display = (!val || r.dataset.docente === val) ? '' : 'none';
            });
        });
    });
</script>
@endsection