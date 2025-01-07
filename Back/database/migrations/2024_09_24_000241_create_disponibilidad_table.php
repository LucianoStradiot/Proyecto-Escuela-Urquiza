<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisponibilidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disponibilidad', function (Blueprint $table) {
            $table->integer('id_disp')->primary();
            $table->integer('id_uc')->nullable();
            $table->integer('id_docente')->nullable();
            $table->integer('id_h_p_d')->nullable();
            $table->integer('id_aula')->nullable();
            $table->integer('id_grado')->nullable();
            $table->string('dia', 50)->nullable();
            $table->time('modulo_inicio')->nullable();
            $table->time('modulo_fin')->nullable();
            
            $table->foreign('id_uc', 'disponibilidad_ibfk_1')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
            $table->foreign('id_docente', 'disponibilidad_ibfk_2')->references('id_docente')->on('docente')->onDelete('cascade');
            $table->foreign('id_h_p_d', 'disponibilidad_ibfk_3')->references('id_h_p_d')->on('horario_previo_docente')->onDelete('cascade');
            $table->foreign('id_aula', 'disponibilidad_ibfk_4')->references('id_aula')->on('aula')->onDelete('cascade');
            $table->foreign('id_grado', 'disponibilidad_ibfk_5')->references('id_grado')->on('grado')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disponibilidad');
    }
}
