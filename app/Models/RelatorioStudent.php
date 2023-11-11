<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RelatorioStudent extends Model
{
    public $items=[];
    public $total_alunos=0;

    public function getRelatorio()
    {
        //\DB::enableQueryLog();
        $method=request('atividade')?'whereHas':'whereDoesntHave';
        $functionFilter=function ($query) {
            if (request('periodo_inicio')):
                $dt = request('periodo_inicio');
                $query->where('celulas.dia', '>=', $dt);
            endif;
    
            if (request('periodo_fim')):
                $dt = request('periodo_fim');
                $query->where('celulas.dia', '<=', $dt);
            endif;
        };
        $queryBase = Student::where('active',1)->$method('celulas', $functionFilter);
        $queryBase->withCount(['celulas'=>$functionFilter]);
        $this->items = $queryBase->get();
        $this->tratarDados();
        //dd(\DB::getQueryLog());      
        return $this;
    }

    /**
     * Tratar ou Configurar alguns dados.
     */

    private function tratarDados()
    {
        $this->total_alunos=count($this->items);

    }

    
}
