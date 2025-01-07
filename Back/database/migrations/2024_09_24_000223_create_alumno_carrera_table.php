<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnoCarreraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_carrera', function (Blueprint $table) {
            $table->integer('id_alumno');
            $table->integer('id_carrera');
            
            $table->primary(['id_alumno', 'id_carrera']);
            $table->foreign('id_alumno', 'fk_alumno_carrera_alumno')->references('id_alumno')->on('alumno')->onDelete('cascade');
            $table->foreign('id_carrera', 'fk_alumno_carrera_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumno_carrera');
    }
}
