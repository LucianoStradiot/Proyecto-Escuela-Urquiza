<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docente', function (Blueprint $table) {
            $table->integer('id_docente')->primary();
            $table->integer('DNI')->nullable();
            $table->string('nombre', 20)->nullable();
            $table->string('apellido', 20)->nullable();
            $table->string('email', 30)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('genero', 10)->nullable();
            $table->date('fecha_nac')->nullable();
            $table->string('nacionalidad', 20)->nullable();
            $table->string('direccion', 30)->nullable();
            $table->integer('id_localidad')->nullable();
            
            $table->foreign('id_localidad', 'docente_ibfk_1')->references('id_localidad')->on('localidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docente');
    }
}
