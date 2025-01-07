<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorarioPrevioDocenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horario_previo_docente', function (Blueprint $table) {
            $table->integer('id_h_p_d')->primary();
            $table->integer('id_docente')->nullable();
            $table->string('dia', 50)->nullable();
            $table->time('hora')->nullable();
            
            $table->foreign('id_docente', 'horario_previo_docente_ibfk_1')->references('id_docente')->on('docente')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horario_previo_docente');
    }
}
