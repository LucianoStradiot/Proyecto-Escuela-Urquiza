<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarreraPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrera_plan', function (Blueprint $table) {
            $table->integer('id_plan');
            $table->integer('id_carrera');
            
            $table->primary(['id_plan', 'id_carrera']);
            $table->foreign('id_plan', 'carrera_plan_ibfk_1')->references('id_plan')->on('plan_estudio');
            $table->foreign('id_carrera', 'carrera_plan_ibfk_2')->references('id_carrera')->on('carrera');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrera_plan');
    }
}
