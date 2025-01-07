<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocenteUcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docente_uc', function (Blueprint $table) {
            $table->integer('id_docente');
            $table->integer('id_uc');
            
            $table->primary(['id_docente', 'id_uc']);
            $table->foreign('id_docente', 'docente_uc_ibfk_1')->references('id_docente')->on('docente')->onDelete('cascade');
            $table->foreign('id_uc', 'docente_uc_ibfk_2')->references('id_uc')->on('unidad_curricular')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docente_uc');
    }
}
