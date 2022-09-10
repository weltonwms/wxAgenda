<?php
namespace App\Helpers;

use App\Models\Disciplina;
use App\Models\Celula;
use App\Models\Aula;
use App\Models\Student;
use App\Models\Systemcount;
use App\Helpers\ConfiguracoesHelper;

class AulaHelper
{
    public $module_id;
    public $student_id;
    private $base;
    private $aulasFeitas;
    private $systemCounts;
    private $celulasAbertas;

    public function start()
    {
        $celula_limit= ConfiguracoesHelper::celula_limit();
        $this->base = Disciplina::with(['aulas' => function ($query) {
            $query->where('module_id', $this->module_id);
        }])->get();

        $this->aulasFeitas = Aula::join('celulas', 'celulas.aula_id', '=', 'aulas.id')
            ->join('celula_student', 'celula_student.celula_id', '=', 'celulas.id')
            ->select('aulas.*', 'celulas.horario', 'celulas.dia', 'celulas.id as celula_id')

            ->where('aulas.module_id', $this->module_id)
            ->where('celula_student.student_id', $this->student_id)
            ->get();
        $this->systemCounts = Systemcount::where('module_id', $this->module_id)->get();

        $this->celulasAbertas = \DB::table('celulas')
            ->leftJoin('celula_student', 'celula_student.celula_id', '=', 'celulas.id')
            ->select(\DB::raw('celulas.id, dia, horario, aula_id, COUNT(celula_student.student_id) as ct'))
            ->groupBy('celulas.id','dia','horario','aula_id')
            ->havingBetween('ct', [1, $celula_limit])
            ->get();
    //dd($this->celulasAbertas->pluck('aula_id'));

    }

    public function filtrar()
    {
        $list = collect();
        foreach ($this->base as $base) {
            if ($base->aulas->isNotEmpty()) {
                $retorno = $base->base ? $this->tratarBase($base) : $this->tratarNaoBase($base);
                $list->push($retorno);


            }
        }
       
        return $list;
    }

    private function tratarBase($base)
    {

        $obj = new \stdClass();
        $obj->id = $base->id;
        $obj->nome = $base->nome;

        $aulasFeitasDisciplina = $this->aulasFeitas->filter(function ($value) use ($base) {
            return $value->disciplina_id == $base->id;
        });
        $maxOrdemFeito = (int)$aulasFeitasDisciplina->max('ordem');

        $nextAula = $base->aulas->first(function ($value) use ($maxOrdemFeito) {
            return $value->ordem == $maxOrdemFeito + 1;
        });

        //add aulas que serão usadas
        $aulasShow = $aulasFeitasDisciplina->unique('id')->map(function ($aula) {
            $objAula = new \stdClass();
            $objAula->id = $aula->id;
            $objAula->sigla = $aula->sigla;
            $objAula->ordem = $aula->ordem;
            $objAula->realizada = 1;

            return $objAula;
        });
        if ($nextAula):
            $objAula = new \stdClass();
            $objAula->id = $nextAula->id;
            $objAula->sigla = $nextAula->sigla;
            $objAula->ordem = $nextAula->ordem;
            $objAula->realizada = 0;
            $aulasShow->push($objAula);
        endif;

       $obj->aulas=$aulasShow->sortBy('ordem');
       return $obj;
    }

    private function tratarNaoBase($base)
    {
        $obj = new \stdClass();
        $obj->id = $base->id;
        $obj->nome = $base->nome;

        $countDisciplina = 1;
        foreach ($this->systemCounts as $systemCount) {
            if ($systemCount->disciplina_id == $base->id) {
                $countDisciplina = $systemCount->contador;
                break;
            }
        }

        $aulaCurrent = $base->aulas->first(function ($value) use ($countDisciplina) {
            return $value->ordem == $countDisciplina;
        });

        $aulasAbertas = $base->aulas->whereIn('id', $this->celulasAbertas->pluck('aula_id'));

        $aulasShow = $aulasAbertas->map(function($aula){
            $objAula = new \stdClass();
            $objAula->id = $aula->id;
            $objAula->sigla = $aula->sigla;
            $objAula->ordem = $aula->ordem;
            $objAula->realizada = $this->isAulaFeita($aula->id);

            return $objAula;
        });
        if ($aulaCurrent) {
            $objAula = new \stdClass();
            $objAula->id = $aulaCurrent->id;
            $objAula->sigla = $aulaCurrent->sigla;
            $objAula->ordem = $aulaCurrent->ordem;
            $objAula->realizada =  $this->isAulaFeita($aulaCurrent->id);
            $aulasShow->push($objAula);
        }

        $obj->aulas=$aulasShow->unique('id')->sortBy('ordem');
        return $obj;

    }

    private function isAulaFeita($aula_id)
    {
        return $this->aulasFeitas->contains('id',$aula_id)?1:0;
    }

}