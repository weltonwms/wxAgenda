<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class RelatorioTeacher extends Model
{
    public $items=[];
    public $total_celulas=0;

    public function getRelatorio()
    {
        //\DB::enableQueryLog();
        $queryBase = Celula::has('students')
            ->join('teachers', 'teachers.id', 'celulas.teacher_id')
            ->select('celulas.dia', 'celulas.horario', 'celulas.teacher_id',
             'teachers.nome as teacher_nome');

             if (request('periodo_inicio')):
                $dt = request('periodo_inicio');
                $queryBase->where('celulas.dia', '>=', $dt);
            endif;
    
            if (request('periodo_fim')):
                $dt = request('periodo_fim');
                $queryBase->where('celulas.dia', '<=', $dt);
            endif;

            if (request('teacher_id')):
                $queryBase->where('celulas.teacher_id', request('teacher_id'));
            endif;

            $queryBase->withCount('students');
            $queryBase->orderBy('celulas.dia')->orderBy('celulas.horario');
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
        $this->total_celulas=count($this->items);

    }
}
