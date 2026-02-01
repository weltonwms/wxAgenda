<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TesteLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testes:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Escreve um log info de teste';

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
        Log::info("Comando TesteLog executado com sucesso");
        $this->info("Verifique o arquivo de log");

        return Command::SUCCESS;
    }
}
