<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnoUcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_uc', function (Blueprint $table) {
            $table->integer('id_alumno');
            $table->integer('id_uc');
            
            $table->primary(['id_alumno', 'id_uc']);
            $table->foreign('id_uc', 'alumno_uc_ibfk_1')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
            $table->foreign('id_alumno', 'alumno_uc_ibfk_2')->references('id_alumno')->on('alumno')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumno_uc');
    }
}
