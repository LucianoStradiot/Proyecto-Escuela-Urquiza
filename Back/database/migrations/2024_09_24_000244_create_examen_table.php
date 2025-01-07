<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examen', function (Blueprint $table) {
            $table->integer('id_examen')->primary();
            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();
            $table->integer('id_aula')->nullable();
            $table->integer('id_docente')->nullable();
            $table->integer('id_uc')->nullable();
            
            $table->foreign('id_uc', 'examenes_ibfk_1')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
            $table->foreign('id_docente', 'examenes_ibfk_2')->references('id_docente')->on('docente')->onDelete('cascade');
            $table->foreign('id_aula', 'examenes_ibfk_3')->references('id_aula')->on('aula')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examen');
    }
}
