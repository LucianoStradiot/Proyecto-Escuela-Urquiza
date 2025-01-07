<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsistenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->integer('id_asistencia')->primary();
            $table->integer('id_alumno')->nullable();
            $table->integer('id_uc')->nullable();
            $table->string('asistencia', 50)->nullable();
            $table->dateTime('fecha')->nullable();
            
            $table->foreign('id_alumno', 'asistencia_ibfk_1')->references('id_alumno')->on('alumno')->onDelete('cascade');
            $table->foreign('id_uc', 'asistencia_ibfk_2')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asistencia');
    }
}
