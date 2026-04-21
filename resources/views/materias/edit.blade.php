@extends('layout')
@section('content')
<h1>Editar Materia</h1>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form action="{{ route('materias.update',$materia->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $materia->nombre) }}" required>
    </div>
    <div class="mb-3">
        <label>Código</label>
        <input type="text" name="codigo" class="form-control" value="{{ old('codigo', $materia->codigo) }}">
    </div>
    <button class="btn btn-primary">Actualizar</button>
</form>
@endsection