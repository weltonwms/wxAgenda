<?php
namespace App\Helpers;
use Carbon\Carbon;
use App\Models\Celula;
use App\Models\Systemcount;
use App\Models\Aula;
use App\Helpers\LevelStudentHelper;

/**
 * Pendente:
 * 1)Filtro de Compatibilidade Aulas Não Base
 */



class AgendaHelper
{
    public $student_id;
    public $aula_id;
    public $module_id;
    private $start;
    private $end;
    private $celulasBase;
    private $systemCounts;

    private $aulaRequest;
    private $celulasMarcadas;
    private $levelStudent;
   

    public function start()
    {
        $this->celulasBase = Celula::has('students', '<', 4)
            ->join('horarios', 'horarios.horario', 'celulas.horario')
            ->leftJoin('aulas', 'aulas.id', 'celulas.aula_id')
            ->select('celulas.*', 'horarios.turno_id', 'aulas.disciplina_id')
            ->with('students')
            ->orderBy('celulas.dia')
            ->orderBy('celulas.horario')
            ->get();

        $this->celulasMarcadas = Celula::join('celula_student', 'celula_student.celula_id', '=', 'celulas.id')
            ->select('celula_id', 'celulas.horario', 'celulas.dia')
            ->where('celula_student.student_id', $this->student_id)
            ->get();

        $this->levelStudent= new LevelStudentHelper($this->student_id,$this->module_id);

        $this->systemCounts = Systemcount::where('module_id', $this->module_id)->get();

        $this->aulaRequest = Aula::find($this->aula_id);

    }

    public function setStart($start)
    {
        if ($start) {
            $this->start = Carbon::createFromDate($start)->format('Y-m-d');
        }
    }

    public function setEnd($end)
    {
        if ($end) {
            $this->end = Carbon::createFromDate($end)->format('Y-m-d');
        }
    }

    public function isRequestValid()
    {
        if (!$this->aula_id) {
            return false;
        }

        return true;
    }

    public function filtrar()
    {
        $base = $this->aulaRequest->disciplina->base;
        $resp = $this->celulasBase->filter(function ($celula) use ($base) {
            $diaHorarioJaMarcado = $this->filtroDiaHorario($celula->dia, $celula->horario);
            if ($diaHorarioJaMarcado->isNotEmpty()) {
                return false; //Não pode estar em 2 lugares ao mesmo tempo.
            }
            if ($base) {
                return $this->isCelulaDisponivelForAulaBase($celula);
            }
            else {
                return $this->isCelulaDisponivelForAulaNaoBase($celula);
            }

        });
        return $resp;

    }

    private function isCelulaDisponivelForAulaBase($celula)
    {
        if(!$this->isValidOrdemBase($celula) ){
            return false;
        }

        if ($celula->aula_id) {
            //estado 2
            return $celula->aula_id == $this->aula_id;
        }
        else {
            //estado 1
            $startCarbon = Carbon::createFromDate($celula->dia)->subDay();
            $endCarbon = Carbon::createFromDate($celula->dia)->addDay();
            $start = $startCarbon->format('Y-m-d');
            $end = $endCarbon->format('Y-m-d');

            $resp = $this->filtroAula($this->aula_id, $start, $end, $celula->turno_id);
            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $output->writeln("<info>{$celula->id} {$this->aula_id} $start $end {$celula->turno_id}</info>");
            $output->writeln("<info>Resposta filtro: {$resp}</info>");
            $output->writeln("<info>celulas base: {$this->celulasBase->pluck('id')}</info>");

            return $resp->isEmpty();

        }

    }

    private function isCelulaDisponivelForAulaNaoBase($celula)
    {
        if ($celula->aula_id) {
            //estado 2
            $filtroCompatibilidade = true; // Implementar filtro
            return $celula->aula_id == $this->aula_id && $filtroCompatibilidade;

        }
        else {
            //estado 1
            $disciplina_id = $this->aulaRequest->disciplina_id;
            $systemCount = $this->getSystemCount($disciplina_id);
            if ($this->aulaRequest->ordem == $systemCount):
                $startCarbon = Carbon::createFromDate($celula->dia)->subDay();
                $endCarbon = Carbon::createFromDate($celula->dia)->addDay();
                $start = $startCarbon->format('Y-m-d');
                $end = $endCarbon->format('Y-m-d');


                $resp = $this->filtroDisciplina($disciplina_id, $start, $end, $celula->turno_id);
                return $resp->isEmpty();
            endif;
            return false;
        }

    }

    private function filtroDisciplina($disciplina_id, $start, $end, $turno_id)
    {
        $filtered = $this->celulasBase
            ->filter(function ($celula) use ($disciplina_id, $start, $end, $turno_id) {
            return $celula->disciplina_id == $disciplina_id &&
            $celula->turno_id == $turno_id &&
            $celula->dia >= $start &&
            $celula->dia <= $end;
        });
        return $filtered;
    }

    private function filtroAula($aula_id, $start, $end, $turno_id)
    {
        //dd($aula_id, $start, $end, $turno_id);
        $filtered = $this->celulasBase
            ->filter(function ($celula) use ($aula_id, $start, $end, $turno_id) {

            return $celula->aula_id == $aula_id &&
            $celula->turno_id == $turno_id &&
            $celula->dia >= $start &&
            $celula->dia <= $end;
        });
        return $filtered;
    }

    private function filtroDiaHorario($dia, $horario)
    {
        $filtered = $this->celulasMarcadas
            ->filter(function ($celula) use ($dia, $horario) {

            return $celula->dia == $dia &&
            $celula->horario == $horario;
        });
        return $filtered;

    }

    private function getSystemCount($disciplina_id)
    {
        $resp = $this->systemCounts->firstWhere('disciplina_id', $disciplina_id);
        if ($resp) {
            return $resp->contador;
        }
        return 1;

    }

    public function mapToEvents($celulas)
    {
        $list = [];
        foreach ($celulas as $celula):
            $key = $celula->dia . " " . $celula->horario;
            $obj = new \stdClass();
            $obj->id = $celula->id;
            $obj->title = '';
            $obj->start = $celula->dia . " " . $celula->horario;


            if (isset($list[$key])) {
                $list[$key]->teachers[] = $celula->teacher_id;
                $list[$key]->celulas[] = $celula->id;

            }
            else {
                $obj->teachers = [$celula->teacher_id];
                $obj->celulas = [$celula->id];
                $list[$key] = $obj;

            }
            $countTeachers = count($list[$key]->teachers);
            $list[$key]->title = '(' . $countTeachers . ')';

        endforeach;


        return array_values($list);
    }

    private function isValidOrdemBase($celula)
    {
        $level=$this->levelStudent->getLevel($celula->dia,$celula->horario);
        $ordemAulaTarget=($this->aulaRequest->ordem -1);
        return $level >=$ordemAulaTarget;
    }

   

}