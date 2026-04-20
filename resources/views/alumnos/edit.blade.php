@extends('layout')

@section('content')
<h1>Editar Alumno</h1>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('alumnos.update', $alumno->id) }}" method="POST">
    @csrf
    @method('PUT')
    @include('alumnos._form')
</form>
@endsection