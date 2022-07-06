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
            $table->string('nome');
            $table->string('email');
            $table->string('telefone');
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->integer('saldo_atual')->nullable()->comment('Créditos para marcar aula');
            $table->unsignedBigInteger('module_id')->nullable();
            $table->unsignedBigInteger('user_id')->unique()->nullable();
            $table->timestamps();
                        
            $table->foreign('module_id')->references('id')->on('modules');
            $table->foreign('user_id')->references('id')->on('users');
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
