<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddN4ToCelulaStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('celula_student', function (Blueprint $table) {
            $table->double('n4')->unsigned()->nullable()->after('n3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('celula_student', function (Blueprint $table) {
            $table->dropColumn('n4');
        });
    }
}
