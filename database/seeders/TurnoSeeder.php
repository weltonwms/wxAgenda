<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TurnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('turnos')->insert([
            ['id' => 1, 'descricao' => 'Matutino'],
            ['id' => 2, 'descricao' => 'Vespertino'],
            ['id' => 3, 'descricao' => 'Noturno'],
        ]);
    }
}
