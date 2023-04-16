<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAulaLinkToCelulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('celulas', function (Blueprint $table) {
            $table->string('aula_link')->after('aula_id')->nullable()
            ->comment('Link Reunião Zoom');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('celulas', function (Blueprint $table) {
            $table->dropColumn('aula_link');
        });
    }
}
