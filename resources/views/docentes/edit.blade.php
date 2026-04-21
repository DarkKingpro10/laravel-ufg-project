@extends('layout')
@section('content')
<h1>Editar Docente</h1>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form action="{{ route('docentes.update',$docente->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $docente->nombre) }}" required>
    </div>
    <button class="btn btn-primary">Actualizar</button>
</form>
@endsection