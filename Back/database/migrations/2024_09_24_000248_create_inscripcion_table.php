<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscripcionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscripcion', function (Blueprint $table) {
            $table->integer('id_inscripcion')->primary();
            $table->dateTime('FechaHora')->nullable();
            $table->integer('id_alumno')->nullable();
            $table->integer('id_carrera')->nullable();
            $table->integer('id_grado')->nullable();
            
            $table->foreign('id_alumno', 'fk_inscripcion_alumno')->references('id_alumno')->on('alumno')->onDelete('cascade');
            $table->foreign('id_carrera', 'fk_inscripcion_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
            $table->foreign('id_grado', 'fk_inscripcion_grado')->references('id_grado')->on('grado')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscripcion');
    }
}
