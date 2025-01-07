<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrelatividadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('correlatividad', function (Blueprint $table) {
            $table->integer('id_correlativa')->primary();
            $table->integer('id_uc')->nullable();
            $table->integer('correlativa')->nullable();
            $table->integer('id_carrera')->nullable();
            
            $table->foreign('id_carrera', 'fk_correlatividad_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
            $table->foreign('correlativa', 'fk_correlatividad_correlativa')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
            $table->foreign('id_uc', 'fk_correlatividad_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('correlatividad');
    }
}
