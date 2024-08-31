<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('celula_id')->constrained()->onDelete('cascade')->unique();
            $table->boolean('tipo_review');
            $table->text('descricao_review'); 
           
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
        Schema::dropIfExists('review_infos');
    }
}
