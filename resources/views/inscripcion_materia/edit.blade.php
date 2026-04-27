@extends('layout')

@section('content')
<div class="container">
    <h1>Editar Inscripción</h1>

    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <form method="POST" action="{{ route('inscripcion-materia.update', $ins->id) }}">
        @csrf @method('PUT')
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Alumno</label>
                <select name="alumno_id" class="form-control" required>
                    @foreach($alumnos as $a)
                    <option value="{{ $a->id }}" {{ $ins->alumno_id == $a->id ? 'selected' : '' }}>{{ $a->nombres }} {{ $a->apellidos }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Ciclo</label>
                <select name="ciclo_id" class="form-control" required>
                    @foreach($ciclos as $c)
                    <option value="{{ $c->id }}" {{ $ins->ciclo_id == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Horario</label>
                <select name="horario_id" class="form-control" required>
                    @foreach($horarios as $h)
                    <option value="{{ $h->id }}" {{ $ins->horario_id == $h->id ? 'selected' : '' }}>
                        {{ $h->materia->nombre ?? '' }} — {{ $h->docente->nombre ?? '' }} ({{ $h->dia }} {{ $h->hora_inicio }}-{{ $h->hora_fin }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <button class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection