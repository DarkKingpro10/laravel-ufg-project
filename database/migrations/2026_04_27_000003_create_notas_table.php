<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscripcion_id')->constrained('alumno_materia')->onDelete('cascade');

            // 4 periodos: lab (10%) y parcial (15%) cada uno
            $table->decimal('lab_p1', 5, 2)->nullable();
            $table->decimal('parc_p1', 5, 2)->nullable();
            $table->decimal('lab_p2', 5, 2)->nullable();
            $table->decimal('parc_p2', 5, 2)->nullable();
            $table->decimal('lab_p3', 5, 2)->nullable();
            $table->decimal('parc_p3', 5, 2)->nullable();
            $table->decimal('lab_p4', 5, 2)->nullable();
            $table->decimal('parc_p4', 5, 2)->nullable();

            $table->timestamps();
            $table->unique('inscripcion_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notas');
    }
};
