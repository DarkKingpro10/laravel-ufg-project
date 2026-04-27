@extends('layout')

@section('content')
<div class="container">
    <h1>Editar Carrera</h1>

    <form action="{{ route('carreras.update', $carrera) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Código</label>
            <input name="codigo" class="form-control" value="{{ $carrera->codigo }}" required>
        </div>
        <div class="mb-3">
            <label>Nombre</label>
            <input name="nombre" class="form-control" value="{{ $carrera->nombre }}" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control">{{ $carrera->descripcion }}</textarea>
        </div>
        <button class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection