<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('');
            $table->string('dni')->default('');
            $table->string('email')->default('')->unique('students_email_unique');
            $table->string('password', 60)->default('');
            $table->string('career')->default('');
            $table->string('profile_photo')->nullable();
            $table->boolean('approved')->default(0);
            $table->string('reset_password_token')->nullable();
            $table->boolean('reset_password_used')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
