@extends('layout')

@section('content')
<div class="container">
    <h1>Inscripciones a Materias</h1>
    <a href="{{ route('inscripcion-materia.create') }}" class="btn btn-primary mb-3">Nueva Inscripción</a>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <form method="GET" action="{{ route('inscripcion-materia.index') }}" class="row g-2 mb-3">
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
        <div class="col-auto">
            <a href="{{ route('inscripcion-materia.index') }}" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>

    <table id="inscripciones-table" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Alumno</th>
                <th>Materia</th>
                <th>Docente</th>
                <th>Día</th>
                <th>Horario</th>
                <th>Ciclo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inscripciones as $i)
            <tr>
                <td>{{ $i->id }}</td>
                <td>
                    @if(isset($i->alumno->id))
                    <a href="{{ route('inscripcion-materia.manageAlumno', ['alumno'=>$i->alumno->id, 'ciclo_id'=>$cicloId ?? '']) }}">{{ $i->alumno->nombres }} {{ $i->alumno->apellidos }}</a>
                    @else
                    {{ $i->alumno->nombres ?? '' }} {{ $i->alumno->apellidos ?? '' }}
                    @endif
                </td>
                <td>{{ $i->horario->materia->nombre ?? '' }}</td>
                <td>{{ $i->horario->docente->nombre ?? '' }}</td>
                <td>{{ $i->horario->dia ?? '' }}</td>
                <td>{{ $i->horario->hora_inicio }} - {{ $i->horario->hora_fin }}</td>
                <td>{{ $i->ciclo->nombre ?? '' }}</td>
                <td>
                    <a href="{{ route('inscripcion-materia.edit', $i->id) }}" class="btn btn-sm btn-secondary">Editar</a>
                    <a href="{{ route('inscripcion-materia.manageAlumno', ['alumno'=>$i->alumno->id, 'ciclo_id'=>$cicloId ?? '']) }}" class="btn btn-sm btn-info">Gestionar alumno</a>
                    <form action="{{ route('inscripcion-materia.destroy', $i->id) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar inscripcion?')">Eliminar</button>
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
    $(function() {
        $('#inscripciones-table').DataTable({
            responsive: true
        });
    });
</script>
@endsection