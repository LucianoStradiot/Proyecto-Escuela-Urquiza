<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnoGradoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_grado', function (Blueprint $table) {
            $table->integer('id_alumno');
            $table->integer('id_grado');
            
            $table->primary(['id_alumno', 'id_grado']);
            $table->foreign('id_alumno', 'fk_alumno_grado_alumno')->references('id_alumno')->on('alumno')->onDelete('cascade');
            $table->foreign('id_grado', 'fk_alumno_grado_grado')->references('id_grado')->on('grado')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumno_grado');
    }
}
