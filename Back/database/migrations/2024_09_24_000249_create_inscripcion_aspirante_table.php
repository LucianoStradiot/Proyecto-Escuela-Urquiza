<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscripcionAspiranteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscripcion_aspirante', function (Blueprint $table) {
            $table->integer('id_inscripcion')->primary();
            $table->dateTime('fechahora')->nullable();
            $table->integer('id_aspirante')->nullable();
            $table->integer('id_carrera')->nullable();
            $table->integer('id_grado')->nullable();
            
            $table->foreign('id_aspirante', 'fk2_inscripcion_aspirante')->references('id_aspirante')->on('aspirante')->onDelete('cascade');
            $table->foreign('id_carrera', 'fk2_inscripcion_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
            $table->foreign('id_grado', 'fk2_inscripcion_grado')->references('id_grado')->on('grado')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscripcion_aspirante');
    }
}
