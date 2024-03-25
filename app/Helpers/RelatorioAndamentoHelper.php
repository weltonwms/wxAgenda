<?php

namespace App\Helpers;



class RelatorioAndamentoHelper
{
    private $alunos = [];
    private $aulasFeitas = [];
    private $aulasTarget = [];

    private $aulasTargetModel= []; //mesmo que $aulasTarget, mas num formato diferente
    private $module_id;
    private $disciplina_id;

   
    public function start($module_id, $disciplina_id=null)
    {
        $this->module_id = $module_id;
        $this->disciplina_id = $disciplina_id;
        $this->setAulasTarget();
        $this->setAulasFeitas();
        $this->setAlunos();
        $this->registrarAulaSfeitasEmAlunos();
        $this->alunos = $this->alunos->sortByDesc('percentualComplete');
    }

    public function setAulasFeitas()
    {
        $query = \DB::table('celula_student')
            ->select('student_id', 'presenca', 'aula_id', 'disciplina_id', 'module_id')
            ->join('celulas', 'celulas.id', '=', 'celula_student.celula_id')
            ->join('aulas', 'aulas.id', '=', 'celulas.aula_id')
            ->where('presenca', 1)
            ->where('module_id', $this->module_id);
        if($this->disciplina_id){
            $query->where('disciplina_id', $this->disciplina_id);
        }
        $this->aulasFeitas = $query->orderBy('student_id')
            ->orderBy('aula_id')
            ->distinct()
            ->get();       
    }


    public function setAulasTarget()
    {
        $query = \DB::table('aulas')
            ->where('module_id', $this->module_id);
        if($this->disciplina_id){
            $query->where('disciplina_id', $this->disciplina_id);
        }
        $aulas = $query->get();
        $this->aulasTargetModel = $aulas;
        $this->aulasTarget = $aulas->mapWithKeys(function ($aula) {
            return [$aula->id => 0];
        });
    }

    public function setAlunos()
    {      
        $students = \DB::table('students')
        ->where('module_id', $this->module_id)
        ->where('active',1)
        ->get();

        $this->alunos = $students->mapWithKeys(function ($student) {
            $obj = new \stdClass();
            $obj->id = $student->id;
            $obj->nome = $student->nome;
            $obj->module_id = $student->module_id;
            $obj->aulasTarget = $this->aulasTarget->toArray();
            $obj->countAulas = count($this->aulasTarget);
            $obj->countFeitas = 0;
            $obj->percentualComplete = 0;
            return [$student->id => $obj];
        });            
    }

    public function registrarAulaSfeitasEmAlunos()
    {       
        foreach($this->aulasFeitas as $aulaFeita):
           if(isset($this->alunos[$aulaFeita->student_id]->aulasTarget[$aulaFeita->aula_id])):
            $this->alunos[$aulaFeita->student_id]->aulasTarget[$aulaFeita->aula_id] = 1;
            $this->alunos[$aulaFeita->student_id]->countFeitas++;
            //calculo percentual parcial
            $atividadesRealizadas = $this->alunos[$aulaFeita->student_id]->countFeitas;
            $totalAtividades = $this->alunos[$aulaFeita->student_id]->countAulas;
            $percent = ($atividadesRealizadas / $totalAtividades) * 100;
            $this->alunos[$aulaFeita->student_id]->percentualComplete =  $percent;           
           endif;      

        endforeach;       
    }


    public function getAlunos()
    {
        return $this->alunos?$this->alunos->values():[];
    }

    public function mapeamento($aulasTargetObject)
    {
        $map= $this->aulasTargetModel->map(function($aula) use($aulasTargetObject){
            $obj = new \stdClass();
            $obj->sigla = $aula->sigla;
            $obj->value = isset($aulasTargetObject[$aula->id])?$aulasTargetObject[$aula->id]:0;
            return $obj;
        });
        return $map;
    }



}
