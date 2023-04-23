<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();           
            $table->unsignedBigInteger('sender_id'); //Remetente           
            $table->unsignedBigInteger('recipient_id'); //Destinatário           
            $table->string('subject'); //Assunto
            $table->text('body'); //Mensagem em si
            $table->boolean('is_read')->default(false); 
            $table->boolean('sender_delete')->default(false)->comment('Apagada por Sender?'); 
            $table->boolean('recipient_delete')->default(false)->comment('Apagada por Recipient?');                
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('recipient_id')->references('id')->on('users');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
