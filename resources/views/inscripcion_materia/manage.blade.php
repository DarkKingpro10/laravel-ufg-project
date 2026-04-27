@extends('layout')

@section('content')
<div class="container">
    <h1>Gestionar Inscripciones - {{ $alumno->nombres }} {{ $alumno->apellidos }}</h1>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <form method="GET" action="{{ route('inscripcion-materia.manageAlumno', ['alumno'=>$alumno->id]) }}" class="mb-3">
        <label>Ciclo</label>
        <select name="ciclo_id" onchange="this.form.submit()" class="form-control">
            @foreach($ciclos as $c)
            <option value="{{ $c->id }}" {{ (string)$c->id === (string)$cicloId ? 'selected' : '' }}>{{ $c->nombre }}</option>
            @endforeach
        </select>
    </form>

    <form method="POST" action="{{ route('inscripcion-materia.storeAlumno') }}">
        @csrf
        <input type="hidden" name="alumno_id" value="{{ $alumno->id }}">
        <input type="hidden" name="ciclo_id" value="{{ $cicloId }}">

        <div class="mb-3">
            <label>Horarios</label>
            <div class="list-group">
                @foreach($horarios as $h)
                <label class="list-group-item">
                    <input type="checkbox" name="horario_ids[]" value="{{ $h->id }}" {{ in_array($h->id,$selected) ? 'checked' : '' }}>
                    <strong>{{ $h->materia->nombre ?? '' }}</strong> — {{ $h->docente->nombre ?? '' }} ({{ $h->dia }} {{ $h->hora_inicio }}-{{ $h->hora_fin }})
                </label>
                @endforeach
            </div>
        </div>

        <button class="btn btn-primary">Guardar inscripciones</button>
    </form>
</div>
@endsection