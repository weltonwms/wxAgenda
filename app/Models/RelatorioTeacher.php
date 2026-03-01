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

            $this->setTeacherIdOnQueryRelatorio($queryBase);

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

    private function setTeacherIdOnQueryRelatorio($query)
    {
        $user = auth()->user();
        // Regra 1: Professor só vê seus próprios dados
        if ($user->isTeacher) {
            return $query->where('celulas.teacher_id', $user->getIdTeacher());
        }

        // Regra 2: Admin pode filtrar por teacher_id (opcional)
        if ($user->isAdm && request()->filled('teacher_id')) {
            return $query->where('celulas.teacher_id', request('teacher_id'));
        }

        // Regra 3: Admin sem filtro -> vê tudo (não aplica where)
        return $query;
    }
}
