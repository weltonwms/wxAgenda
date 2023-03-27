<?php
namespace App\Helpers;
use App\Helpers\AgendaHelper;
use Carbon\Carbon;
use App\Helpers\ConfiguracoesHelper;

class StoreStudentHelper
{
    private $agendaHelper;

    public function validarStore($student, $celula, $aulaRequest)
    {
        $this->agendaHelper = new AgendaHelper();
        $this->agendaHelper->student_id = $student->id;
        $this->agendaHelper->module_id = $aulaRequest->module_id;
        $this->agendaHelper->setAulaRequest($aulaRequest);
        $this->agendaHelper->setStart($celula->dia);
        $this->agendaHelper->setEnd($celula->dia);
        $this->agendaHelper->start();
        // throw new \Exception('Testando um erro');
        $this->isActiveAgendamento();
        $this->hasCredit($student);
        $this->limiteCelula($celula);
        $this->mesmoDiaHorario($celula);
        $this->validModule($student);
        $this->celulaEstado2($celula, $aulaRequest->id);
        $this->filtroAulaDisciplina($celula);
        $this->validOrdemBase($celula,$student);
        
        $this->ordemSystemCount($celula);
        //Adm pode marcar datas no passado; por enqunto
        if(!auth()->user()->isAdm){
            $this->isDateFuture($celula->dia,$celula->horario);
        }

    }

    public function getLevelStudent($celula)
    {
        if(!$this->agendaHelper):
            throw new \Exception("Não é possível chamar getLevelStudent() sem chamar validarStore()");
        endif;
        return $this->agendaHelper->getLevelStudent($celula);
    }

    private function isActiveAgendamento()
    {
        $isActiveAgendamento=ConfiguracoesHelper::agendamento_ativo();
        if (!$isActiveAgendamento):
            throw new \Exception("Agendamento Bloqueado por Administrador!");
        endif;
    }

    private function hasCredit($student)
    {
        if ($student->saldo_atual < 1):
            throw new \Exception("Créditos Insuficientes");
        endif;
    }

    private function limiteCelula($celula)
    {
        $celula_limit= ConfiguracoesHelper::celula_limit();
        $msg="";
        if($celula->aula_individual):
            //Para aulas individuais não importa o limite configurado, sempre o limite será de um aluno
            $celula_limit=1;
            $msg="<br>Sala de Aula Individual";
        endif;
        if ($celula->students->count() >= $celula_limit):
            throw new \Exception("Limite de $celula_limit alunos por sala de Aula Estourado!".$msg);
        endif;
    }

    private function mesmoDiaHorario($celula)
    {

        $diaHorarioJaMarcado = $this->agendaHelper->filtroDiaHorario($celula->dia, $celula->horario);
        if ($diaHorarioJaMarcado->isNotEmpty()) {
            //Não pode estar em 2 lugares ao mesmo tempo.
            throw new \Exception("Já existe Agendamento para esse mesmo dia e horário");
        }

    }

    private function validModule($student)
    {
        $aulaRequest = $this->agendaHelper->getAulaRequest();
        if (!$student->isModuleAllowed($aulaRequest->module_id)) {
            throw new \Exception("Aula não Compátivel com seu módulo");
        }

    }

    private function celulaEstado2($celula, $aula_id)
    {
        if ($celula->aula_id && $celula->aula_id != $aula_id) {
            $msg = "Sala já encontra-se ocupada com aula diferente da requisitada!";
            throw new \Exception($msg);
        }

    }

    private function filtroAulaDisciplina($celula)
    {
        if ($celula->aula_id):
            //validação apenas para celula estado 1 (em branco)
            return true;
        endif;
        //procurar nas células 1 dia antes e 1 dia depois (celulas base), filtro por
        //aula ou disciplina
        $aulaRequest = $this->agendaHelper->getAulaRequest();
        $base = $aulaRequest->disciplina->base;
        $turno_id = $celula->horarioObj->turno_id;
        
        $start = $this->getDayIntervalBefore($celula->dia) ;
        $end = $this->getDayIntervalAfter($celula->dia);


        if ($base) {
            $xpt = "Aula";
            $filter = $this->agendaHelper->filtroAula($aulaRequest->id, $start, $end, $turno_id);
        }
        else {
            $xpt = "Disciplina";
            $disciplina_id = $aulaRequest->disciplina_id;
            $module_id = $aulaRequest->module_id;
            $filter = $this->agendaHelper->filtroDisciplina($disciplina_id, $start, $end, $turno_id,$module_id);
        }
        if (!$filter->isEmpty()):
            //Não pode ter já marcado Aula/Disciplina no mesmo turno 1 dia trás e 1 para frente.

            $msg = "$xpt já disponível no período de $start a ";
            $msg .= $end. ", nesse turno!";
            throw new \Exception($msg);
        endif;


    }



    private function validOrdemBase($celula,$student)
    {
        //level aluno compativel para ordem da request da aula_id
        $aulaRequest = $this->agendaHelper->getAulaRequest();
        $base = $aulaRequest->disciplina->base;
        if (!$base):
            //validação apenas para disciplina base;
            return true;
        endif;
        if($student->isModuleAnterior($aulaRequest->module_id)):
            //ignonar validação para módulos anteriores ao corrente.
            return true;
        endif;
        if (!$this->agendaHelper->isValidOrdemBase($celula)) {
            $msg = "A aula requisitada é superior ao seu nível Atual!";
            throw new \Exception($msg);
        }

    }

    private function ordemSystemCount($celula)
    {
        $aulaRequest = $this->agendaHelper->getAulaRequest();
        $base = $aulaRequest->disciplina->base;
        if ($base || $celula->aula_id):
            //validação apenas para aula não base e no estado 1 (em branco);
            return true;
        endif;

        $disciplina_id = $aulaRequest->disciplina_id;
        $systemCount = $this->agendaHelper->getSystemCount($disciplina_id);
        $valid = $aulaRequest->ordem == $systemCount;

        if (!$valid) {
            $msg = "{$aulaRequest->sigla} possui ordem: {$aulaRequest->ordem} que é ";
            $msg .= "diferente da contagem do sistema: $systemCount";
            throw new \Exception($msg);
        }

    }

    private function isDateFuture($dia, $horario)
    {
        $isFuture=$this->agendaHelper->isDateFuture($dia, $horario);
        if (!$isFuture) {
            throw new \Exception("Só é possível agendar Datas Futuras!");
        }

    }

    //Auxiliar de Filtro Aula/Disciplina
    private function getDayIntervalBefore($dia)
    {
        $interval=ConfiguracoesHelper::day_interval_before();
        $startCarbon = Carbon::createFromDate($dia)->subDays($interval);        
        return $startCarbon->format('Y-m-d');
    }

     //Auxiliar de Filtro Aula/Disciplina
    private function getDayIntervalAfter($dia)
    {
        $interval=ConfiguracoesHelper::day_interval_after();
        $endCarbon = Carbon::createFromDate($dia)->addDays($interval);       
        return $endCarbon->format('Y-m-d');       
    }
}