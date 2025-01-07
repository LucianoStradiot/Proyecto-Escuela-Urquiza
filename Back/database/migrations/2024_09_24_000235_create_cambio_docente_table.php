<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCambioDocenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cambio_docente', function (Blueprint $table) {
            $table->integer('id_cambio')->primary();
            $table->integer('id_docente_anterior')->nullable();
            $table->integer('id_docente_nuevo')->nullable();
            
            $table->foreign('id_docente_anterior', 'cambio_docente_ibfk_1')->references('id_docente')->on('docente')->onDelete('cascade');
            $table->foreign('id_docente_nuevo', 'cambio_docente_ibfk_2')->references('id_docente')->on('docente')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cambio_docente');
    }
}
