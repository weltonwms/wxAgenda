<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\InactiveStudentHelper;
use Illuminate\Support\Facades\Log;



class InativarAlunosSemRecarga extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:inactivate-without-recharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inativa alunos ativos há mais de 70 dias sem recarga';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        file_put_contents(storage_path('logs/inactive_students.log'), "\n=============================================================================================================\n", FILE_APPEND);
        Log::channel('inactive_students')->info("Iniciando Verificação de Alunos Sem Recarga");

        $total = InactiveStudentHelper::inativarAlunosPorFaltaRecarga();

        $this->info("Total de alunos inativados: {$total}");

        return Command::SUCCESS;
    }
}
