<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancellationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancellations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->timestamp('data_acao');
            $table->time('horario');
            $table->date('dia');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('aula_id');
            $table->boolean('by_adm')->default(false);

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');;
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cancellations');
    }
}
