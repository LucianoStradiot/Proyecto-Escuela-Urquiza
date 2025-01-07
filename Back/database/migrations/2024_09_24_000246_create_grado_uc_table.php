<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradoUcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grado_uc', function (Blueprint $table) {
            $table->integer('id_grado');
            $table->integer('id_uc');
            
            $table->primary(['id_grado', 'id_uc']);
            $table->foreign('id_uc', 'fk_grado_uc_uc')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
            $table->foreign('id_grado', 'grado_uc_FK')->references('id_grado')->on('grado')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grado_uc');
    }
}
