<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCelulaStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('celula_student', function (Blueprint $table) {
            $table->unsignedBigInteger('celula_id');
            $table->unsignedBigInteger('student_id');
            $table->primary(['celula_id', 'student_id']);

            $table->foreign('celula_id')->references('id')->on('celulas');
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('celula_student');
    }
}
