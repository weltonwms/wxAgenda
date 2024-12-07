<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id')->unique();
            $table->string('user_email');
            $table->integer('course_credits');

            $table->string('user_name')->nullable(); 
            $table->timestamp('pedido_date')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->decimal('price',10,2)->nullable();                       
            
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
        Schema::dropIfExists('payments');
    }
}
