<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadCurricularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidad_curricular', function (Blueprint $table) {
            $table->integer('id_uc')->primary();
            $table->string('Unidad_Curricular', 60)->nullable();
            $table->string('Tipo', 20)->nullable();
            $table->integer('HorasSem')->nullable();
            $table->integer('HorasAnual')->nullable();
            $table->string('Formato', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidad_curricular');
    }
}
