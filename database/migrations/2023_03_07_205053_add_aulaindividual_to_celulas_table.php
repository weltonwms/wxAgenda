<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAulaindividualToCelulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('celulas', function (Blueprint $table) {
            $table->tinyInteger('aula_individual')->after('aula_id')->default(0)
            ->comment('Se a Céulua de Aula é individual ou não');
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
            $table->dropColumn('aula_individual');
        });
    }
}
