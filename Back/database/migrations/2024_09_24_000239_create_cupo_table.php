<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupo', function (Blueprint $table) {
            $table->integer('id_cupo')->primary();
            $table->integer('id_carrera')->nullable();
            $table->year('ano_lectivo', 4)->nullable();
            $table->integer('cupos_disp')->default(0);
            
            $table->foreign('id_carrera', 'fk_cupos_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cupo');
    }
}
