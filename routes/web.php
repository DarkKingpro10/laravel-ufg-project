<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\HorarioController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('alumnos', AlumnoController::class);
Route::resource('docentes', DocenteController::class);
Route::resource('materias', MateriaController::class);
Route::resource('horarios', HorarioController::class)->except(['create', 'show']);
Route::get('horarios/inscribir', [HorarioController::class, 'inscribirForm'])->name('horarios.inscribir');
Route::post('horarios/inscribir', [HorarioController::class, 'store'])->name('horarios.store');
