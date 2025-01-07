<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnoPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_plan', function (Blueprint $table) {
            $table->integer('id_plan');
            $table->integer('id_alumno');
            
            $table->primary(['id_plan', 'id_alumno']);
            $table->foreign('id_plan', 'alumno_plan_ibfk_1')->references('id_plan')->on('plan_estudio');
            $table->foreign('id_alumno', 'alumno_plan_ibfk_2')->references('id_alumno')->on('alumno');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumno_plan');
    }
}
