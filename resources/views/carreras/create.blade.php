@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nueva Carrera</h1>

    <form action="{{ route('carreras.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Código</label>
            <input name="codigo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nombre</label>
            <input name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>
        <button class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection