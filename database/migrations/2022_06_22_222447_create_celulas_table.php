<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCelulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('celulas', function (Blueprint $table) {
            $table->id();
            $table->time('horario');
            $table->date('dia');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('aula_id')->nullable();
            $table->integer('aula_level')->nullable();

            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('horario')->references('horario')->on('horarios');
            $table->foreign('aula_id')->references('id')->on('aulas');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('celulas');
    }
}
