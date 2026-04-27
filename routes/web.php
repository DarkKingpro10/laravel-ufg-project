<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\InscripcionMateriaController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('alumnos', AlumnoController::class);
Route::resource('docentes', DocenteController::class);
Route::resource('materias', MateriaController::class);
Route::resource('horarios', HorarioController::class)->except(['create', 'show']);
Route::get('horarios/inscribir', [HorarioController::class, 'inscribirForm'])->name('horarios.inscribir');
Route::post('horarios/inscribir', [HorarioController::class, 'store'])->name('horarios.store');

Route::resource('carreras', CarreraController::class);
Route::get('inscripciones', [CarreraController::class, 'inscripciones'])->name('inscripciones.index');
Route::post('inscripciones', [CarreraController::class, 'storeInscripcion'])->name('inscripciones.store');
Route::delete('inscripciones/{id}', [CarreraController::class, 'destroyInscripcion'])->name('inscripciones.destroy');

// Inscripcion de alumnos a materias (horarios)
Route::resource('inscripcion-materia', InscripcionMateriaController::class)->except(['show']);
Route::get('inscripcion-materia/alumno/{alumno}', [InscripcionMateriaController::class, 'manageAlumno'])->name('inscripcion-materia.manageAlumno');
Route::post('inscripcion-materia/alumno', [InscripcionMateriaController::class, 'storeAlumno'])->name('inscripcion-materia.storeAlumno');
