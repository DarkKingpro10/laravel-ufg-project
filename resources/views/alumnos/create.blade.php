@extends('layout')

@section('content')
<h1>Agregar Alumno</h1>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('alumnos.store') }}" method="POST">
    @csrf
    @include('alumnos._form')
</form>
@endsection