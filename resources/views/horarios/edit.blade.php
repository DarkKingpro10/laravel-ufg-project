@extends('layout')

@section('content')
<h1>Editar horario</h1>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form action="{{ route('horarios.update', $horario->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Docente</label>
        <select name="docente_id" class="form-select" required>
            <option value="">-- Seleccione --</option>
            @foreach($docentes as $d)
            <option value="{{ $d->id }}" {{ $horario->docente_id == $d->id ? 'selected' : '' }}>{{ $d->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Materia</label>
        <select name="materia_id" class="form-select" required>
            <option value="">-- Seleccione --</option>
            @foreach($materias as $m)
            <option value="{{ $m->id }}" {{ $horario->materia_id == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="row g-2 mb-2">
        <div class="col-md-3">
            <label>Día</label>
            <select name="dia" class="form-select" required>
                @foreach($dias as $d)
                <option value="{{ $d }}" {{ $horario->dia == $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>Hora inicio</label>
            <input type="time" name="hora_inicio" value="{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label>Hora fin</label>
            <input type="time" name="hora_fin" value="{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label>Aula</label>
            <input type="text" name="aula" value="{{ $horario->aula }}" class="form-control">
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('horarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

@endsection