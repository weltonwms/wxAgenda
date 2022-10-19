<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditProvidedToCancellationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cancellations', function (Blueprint $table) {
            $table->tinyInteger('credit_provided')->after('by_adm')->default(1)
            ->comment('Créditos devolvidos para o aluno');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cancellations', function (Blueprint $table) {
            $table->dropColumn('credit_provided');
        });
    }
}
