@extends('layout')

@section('content')
<div class="container">
    <h1>Nueva Inscripción a Materias</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('inscripcion-materia.store') }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Alumno</label>
                <select name="alumno_id" class="form-control" required>
                    <option value="">-- seleccionar --</option>
                    @foreach($alumnos as $a)
                    <option value="{{ $a->id }}">{{ $a->nombres }} {{ $a->apellidos }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Ciclo</label>
                <select name="ciclo_id" class="form-control" required>
                    <option value="">-- seleccionar --</option>
                    @foreach($ciclos as $c)
                    <option value="{{ $c->id }}" {{ $c->activo ? 'selected' : '' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Horarios (selecciona hasta 5 materias distintas)</label>
            <div class="list-group">
                @foreach($horarios as $h)
                <label class="list-group-item">
                    <input type="checkbox" name="horario_ids[]" value="{{ $h->id }}">
                    <strong>{{ $h->materia->nombre ?? '' }}</strong>
                    — {{ $h->docente->nombre ?? '' }}
                    ({{ $h->dia }} {{ $h->hora_inicio }}-{{ $h->hora_fin }})
                </label>
                @endforeach
            </div>
        </div>

        <button class="btn btn-success">Inscribir</button>
    </form>
</div>
@endsection