<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarreraUcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrera_uc', function (Blueprint $table) {
            $table->integer('id_carrera');
            $table->integer('id_uc');
            
            $table->primary(['id_carrera', 'id_uc']);
            $table->foreign('id_carrera', 'fk_carrera_uc_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
            $table->foreign('id_uc', 'fk_carrera_uc_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrera_uc');
    }
}
