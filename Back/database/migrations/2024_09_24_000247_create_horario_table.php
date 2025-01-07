<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horario', function (Blueprint $table) {
            $table->integer('id_horario')->primary();
            $table->string('dia', 50)->nullable();
            $table->time('modulo_inicio')->nullable();
            $table->time('modulo_fin')->nullable();
            $table->string('modalidad', 50)->nullable();
            $table->integer('id_disp')->nullable()->index('horario_ibfk_4');
            $table->integer('id_uc')->nullable();
            $table->integer('id_aula')->nullable();
            $table->integer('id_grado')->nullable();
            
            $table->foreign('id_aula', 'horario_ibfk_1')->references('id_aula')->on('aula')->onDelete('cascade');
            $table->foreign('id_grado', 'horario_ibfk_2')->references('id_grado')->on('grado')->onDelete('cascade');
            $table->foreign('id_uc', 'horario_ibfk_3')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horario');
    }
}
