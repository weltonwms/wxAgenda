<?php
namespace App\Helpers;
use App\Helpers\AgendaHelper;
use Carbon\Carbon;

class StoreStudentHelper
{
    private $agendaHelper;

    public function validarStore($student, $celula, $aula_id)
    {
      $this->agendaHelper=new AgendaHelper();
      $this->agendaHelper->student_id=$student->id;
      $this->agendaHelper->module_id=$student->module_id;
      $this->agendaHelper->aula_id=$aula_id;
      $this->agendaHelper->setStart($celula->dia);
      $this->agendaHelper->setEnd($celula->dia);
      $this->agendaHelper->start();
     // throw new \Exception('Testando um erro');
       $this->limiteCelula($celula);
       $this->mesmoDiaHorario($celula);
       $this->validModule($student);
       $this->celulaEstado2($celula,$aula_id);
       $this->filtroAulaDisciplina($celula);
       $this->validOrdemBase($celula);
       $this->ordemSystemCount($celula);

    }

    private function limiteCelula($celula)
    {
        $limite=4;
        if($celula->students->count()>=$limite):
            throw new \Exception("Limite de $limite alunos por sala de Aula Estourado!");
        endif;
    }

    private function mesmoDiaHorario($celula)
    {
        
        $diaHorarioJaMarcado=$this->agendaHelper->filtroDiaHorario($celula->dia,$celula->horario);
        if ($diaHorarioJaMarcado->isNotEmpty()) {
            //Não pode estar em 2 lugares ao mesmo tempo.
            throw new \Exception("Já existe Agendamento para esse mesmo dia e horário");
        }

    }

    private function validModule($student)
    {
        $aulaRequest= $this->agendaHelper->getAulaRequest();
        if($aulaRequest->module_id!=$student->module_id){
            throw new \Exception("Aula não Compátivel com seu módulo");
        }
        
    }

    private function celulaEstado2($celula,$aula_id)
    {
        if($celula->aula_id && $celula->aula_id!=$aula_id){
            $msg="Sala já encontra-se ocupada com aula diferente da requisitada!";
            throw new \Exception($msg);
        }

    }

    private function filtroAulaDisciplina($celula)
    {
        if($celula->aula_id):
            //validação apenas para celula estado 1 (em branco)
            return true;
        endif;
        //procurar nas células 1 dia antes e 1 dia depois (celulas base), filtro por
        //aula ou disciplina
        $aulaRequest= $this->agendaHelper->getAulaRequest();
        $base=$aulaRequest->disciplina->base;
        $turno_id=$celula->horarioObj->turno_id;
        $startCarbon = Carbon::createFromDate($celula->dia)->subDay();
        $endCarbon = Carbon::createFromDate($celula->dia)->addDay();
        $start = $startCarbon->format('Y-m-d');
        $end = $endCarbon->format('Y-m-d');
        if($base){
            $xpt="Aula";
            $filter=$this->agendaHelper->filtroAula($aulaRequest->id,$start,$end,$turno_id);
        }
        else{
            $xpt="Disciplina";
            $disciplina_id=$aulaRequest->disciplina_id;
            $filter=$this->agendaHelper->filtroDisciplina($disciplina_id,$start,$end,$turno_id);
        }
        if(!$filter->isEmpty()):
             //Não pode ter já marcado Aula/Disciplina no mesmo turno 1 dia trás e 1 para frente.
             
             $msg="$xpt já disponível no período de {$startCarbon->format('d.m.Y')} a ";
             $msg.=$endCarbon->format('d.m.Y').", nesse turno!";
             throw new \Exception($msg);
        endif;
       

    }

    

    private function validOrdemBase($celula)
    {
        //level aluno compativel para ordem da request da aula_id
        $aulaRequest= $this->agendaHelper->getAulaRequest();
        $base=$aulaRequest->disciplina->base;
        if(!$base):
            //validação apenas para disciplina base;
            return true;
        endif;
        if(!$this->agendaHelper->isValidOrdemBase($celula)){
            $msg="A aula requisitada é superior ao seu nível Atual!";
            throw new \Exception($msg);
        }

    }

    private function ordemSystemCount($celula)
    {
        $aulaRequest= $this->agendaHelper->getAulaRequest();
        $base=$aulaRequest->disciplina->base;
        if($base || $celula->aula_id):
            //validação apenas para aula não base e no estado 1 (em branco);
            return true;
        endif;
        
        $disciplina_id = $aulaRequest->disciplina_id;
        $systemCount = $this->agendaHelper->getSystemCount($disciplina_id);
        $valid=$aulaRequest->ordem == $systemCount;

        if(!$valid){
            $msg="{$aulaRequest->sigla} possui ordem: {$aulaRequest->ordem} que é ";
            $msg.="diferente da contagem do sistema: $systemCount";
            throw new \Exception($msg);
        }

    }
}