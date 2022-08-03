<?php
namespace App\Helpers;

use App\Models\Disciplina;
use App\Models\Celula;
use App\Models\Aula;
use App\Models\Student;
use App\Models\Systemcount;


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

        $this->celulasAbertas=\DB::table('celulas')
        ->leftJoin('celula_student', 'celula_student.celula_id', '=', 'celulas.id')
        ->select(\DB::raw('celulas.id, dia, horario, aula_id, COUNT(celula_student.student_id) as ct'))
        ->groupBy('celulas.id')
        ->havingBetween('ct', [1, 4])
        ->get();
        //dd($this->celulasAbertas->pluck('aula_id'));

    }

    public function filtrar()
    {
        $list = collect();
        foreach ($this->base as $base) {
            if ($base->aulas->isNotEmpty()) {
                $base->base ? $this->tratarBase($base) : $this->tratarNaoBase($base);
                $list->push($base);


            }
        }
       return $list;
    }

    private function tratarBase($base)
    {

        $aulasFeitasDisciplina = $this->aulasFeitas->filter(function ($value) use ($base) {
            return $value->disciplina_id == $base->id;
        });
        $maxOrdemFeito = (int)$aulasFeitasDisciplina->max('ordem');

        $nextAula = $base->aulas->first(function ($value) use ($maxOrdemFeito) {
            return $value->ordem == $maxOrdemFeito + 1;
        });
        //add aulas que serão usadas
        $base->aulasShow = $aulasFeitasDisciplina->unique('id');
        $base->aulasShow->push($nextAula);

        $base->tipoTeste = 'trataBase';
    }

    private function tratarNaoBase($base)
    {
        $countDisciplina = 0;
        foreach ($this->systemCounts as $systemCount) {
            if ($systemCount->disciplina_id == $base->id) {
                $countDisciplina = $systemCount->contador;
                break;
            }
        }

        $aulaCurrent = $base->aulas->first(function ($value) use ($countDisciplina) {
            return $value->ordem == $countDisciplina;
        });

        $aulasAbertas=$base->aulas->whereIn('id',$this->celulasAbertas->pluck('aula_id'));
       
        $base->aulasShow = $aulasAbertas;
        $base->aulasShow->push($aulaCurrent);

        $base->tipoTeste = 'noteBase';
       
       
    }

}