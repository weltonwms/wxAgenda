<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInfoToCelulaStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('celula_student', function (Blueprint $table) {
            $table->boolean('presenca')->after('student_id')->nullable();
            $table->double('n1')->unsigned()->nullable()->after('presenca');
            $table->double('n2')->unsigned()->nullable()->after('n1');
            $table->double('n3')->unsigned()->nullable()->after('n2');
            $table->text('feedback')->nullable()->after('n3');
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
            $table->dropColumn('presenca');
            $table->dropColumn('n1');
            $table->dropColumn('n2');
            $table->dropColumn('n3');
            $table->dropColumn('feedback');
        });
    }
}
