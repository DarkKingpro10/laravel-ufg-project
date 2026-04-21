@extends('layout')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard</h1>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <a href="{{ route('docentes.index') }}" class="card text-decoration-none text-dark">
                <div class="card-body">
                    <h5 class="card-title">Catálogos</h5>
                    <p class="card-text mb-0">Docentes & Materias</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('alumnos.index') }}" class="card text-decoration-none text-dark">
                <div class="card-body">
                    <h5 class="card-title">Alumnos</h5>
                    <p class="card-text mb-0">Gestión de alumnos</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('horarios.index') }}" class="card text-decoration-none text-dark">
                <div class="card-body">
                    <h5 class="card-title">Horarios</h5>
                    <p class="card-text mb-0">Inscripciones por docente</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="#" class="card text-decoration-none text-dark">
                <div class="card-body">
                    <h5 class="card-title">Configuración</h5>
                    <p class="card-text mb-0">Opciones del sistema</p>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection