@extends('layout')

@section('content')
<div class="container">
    <h1>Notas</h1>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <form method="POST" action="{{ route('notas.populate') }}" style="display:inline-block">
                @csrf
                <button class="btn btn-outline-primary btn-sm">Poblar notas (crear filas faltantes)</button>
            </form>
        </div>
        <div class="text-muted small">Sugerencia: filtra por materia y ciclo para ver alumnos inscritos.</div>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="materia_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Filtrar por materia --</option>
                @foreach($materias as $m)
                <option value="{{ $m->id }}" {{ (string)($materiaId ?? '') === (string)$m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="ciclo_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Filtrar por ciclo --</option>
                @foreach($ciclos as $c)
                <option value="{{ $c->id }}" {{ (string)($cicloId ?? '') === (string)$c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <table id="notas-table" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Alumno</th>
                <th>Materia</th>
                <th>Ciclo</th>
                <th>Promedio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notas as $n)
            <tr>
                <td>{{ $n->id }}</td>
                <td><a href="{{ route('notas.manageAlumno',['alumno'=>$n->inscripcion->alumno->id,'ciclo_id'=>$n->inscripcion->ciclo_id]) }}">{{ $n->inscripcion->alumno->nombres }} {{ $n->inscripcion->alumno->apellidos }}</a></td>
                <td>{{ $n->inscripcion->horario->materia->nombre ?? '' }}</td>
                <td>{{ $n->inscripcion->ciclo->nombre ?? '' }}</td>
                <td>{{ number_format($n->promedio(),2) }}</td>
                <td>
                    <a href="{{ route('notas.edit',$n->id) }}" class="btn btn-sm btn-secondary">Editar</a>
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
        $('#notas-table').DataTable({
            responsive: true
        });
    });
</script>
@endsection