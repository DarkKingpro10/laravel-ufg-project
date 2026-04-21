@extends('layout')
@section('content')
<h1>Crear Materia</h1>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form action="{{ route('materias.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
    </div>
    <div class="mb-3">
        <label>Código</label>
        <input type="text" name="codigo" class="form-control" value="{{ old('codigo') }}">
    </div>
    <button class="btn btn-primary">Guardar</button>
</form>
@endsection