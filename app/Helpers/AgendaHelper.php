<?php
namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Celula;
use App\Models\Systemcount;
use App\Models\Aula;
use App\Helpers\LevelStudentHelper;
use App\Helpers\ConfiguracoesHelper;

/**
 * Pendente:
 * 1)Filtro de Compatibilidade Aulas Não Base
 */



class AgendaHelper
{
    public $student_id;
    public $module_id;
    private $aula_id;
    private $start;
    private $end;
    private $celulasBase;
    private $systemCounts;

    private $aulaRequest;
    private $celulasMarcadas;
    private $levelStudent;


    public function start()
    {
        $celula_limit = ConfiguracoesHelper::celula_limit();
        $queryBase = Celula::has('students', '<', $celula_limit)
            ->join('horarios', 'horarios.horario', 'celulas.horario')
            ->leftJoin('aulas', 'aulas.id', 'celulas.aula_id')
            ->select('celulas.*', 'horarios.turno_id', 'aulas.disciplina_id', 'aulas.module_id');
        if ($this->start) {
            $queryBase->where('celulas.dia', '>=', $this->start);
        }
        if ($this->end) {
            $queryBase->where('celulas.dia', '<=', $this->end);
        }
        $this->celulasBase = $queryBase->with('students')
            ->orderBy('celulas.dia')
            ->orderBy('celulas.horario')
            ->get();

        $queryMarcadas = Celula::join('celula_student', 'celula_student.celula_id', '=', 'celulas.id')
            ->select('celula_id', 'celulas.horario', 'celulas.dia')
            ->where('celula_student.student_id', $this->student_id);
        if ($this->start) {
            $queryMarcadas->where('celulas.dia', '>=', $this->start);
        }
        if ($this->end) {
            $queryMarcadas->where('celulas.dia', '<=', $this->end);
        }
        $this->celulasMarcadas = $queryMarcadas->get();

        $this->levelStudent = new LevelStudentHelper($this->student_id, $this->module_id);

        $this->systemCounts = Systemcount::where('module_id', $this->module_id)->get();

        //$this->aulaRequest = Aula::find($this->aula_id);


    }

    public function setAulaRequest($aula)
    {
        if ($aula) {
            $this->aulaRequest = $aula;
            $this->aula_id = $aula->id;
        }

    }

    public function setStart($start)
    {
        if ($start) {
            $carbonStart = Carbon::createFromDate($start);
            $carbonStart->subDay(); //Filtro pode necessitar pesquisar 1 dia antes
            $this->start = $carbonStart->format('Y-m-d');
        }
    }

    public function setEnd($end)
    {
        if ($end) {
            $carbonEnd = Carbon::createFromDate($end);
            $carbonEnd->addDay(); //Filtro pode necessitar pesquisar 1 dia depois
            $this->end = $carbonEnd->format('Y-m-d');
        }
    }

    public function isRequestValid()
    {
        if (!$this->aula_id) {
            return false;
        }
        if (!$this->module_id) {
            return false;
        }

        return true;
    }

    public function filtrar()
    {
        $base = $this->aulaRequest->disciplina->base;
        $resp = $this->celulasBase->filter(function ($celula) use ($base) {

            $diaHorarioJaMarcado = $this->filtroDiaHorario($celula->dia, $celula->horario);
            $isDateFuture = $this->isDateFuture($celula->dia, $celula->horario);
            if ($diaHorarioJaMarcado->isNotEmpty()) {
                return false; //Não pode estar em 2 lugares ao mesmo tempo.
            }
            if (!$isDateFuture) {
                return false; //Agendamento apenas de datas futuras.
            }
            if ($base) {
                return $this->isCelulaDisponivelForAulaBase($celula);
            } else {
                return $this->isCelulaDisponivelForAulaNaoBase($celula);
            }

        });
        return $resp;

    }

    private function isCelulaDisponivelForAulaBase($celula)
    {
        if (!$this->isValidOrdemBase($celula)) {
            return false;
        }

        if ($celula->aula_id) {
            //estado 2
            return $celula->aula_id == $this->aula_id;
        } else {
            //estado 1
            $startCarbon = Carbon::createFromDate($celula->dia)->subDay();
            $endCarbon = Carbon::createFromDate($celula->dia)->addDay();
            $start = $startCarbon->format('Y-m-d');
            $end = $endCarbon->format('Y-m-d');

            $resp = $this->filtroAula($this->aula_id, $start, $end, $celula->turno_id);

            return $resp->isEmpty();

        }

    }

    private function isCelulaDisponivelForAulaNaoBase($celula)
    {
        if ($celula->aula_id) {
            //estado 2
            $filtroCompatibilidade = true; // Implementar filtro
            return $celula->aula_id == $this->aula_id && $filtroCompatibilidade;

        } else {
            //estado 1
            $disciplina_id = $this->aulaRequest->disciplina_id;
            $systemCount = $this->getSystemCount($disciplina_id);
            if ($this->aulaRequest->ordem == $systemCount):
                $startCarbon = Carbon::createFromDate($celula->dia)->subDay();
                $endCarbon = Carbon::createFromDate($celula->dia)->addDay();
                $start = $startCarbon->format('Y-m-d');
                $end = $endCarbon->format('Y-m-d');
                $module_id = $this->aulaRequest->module_id;

                $resp = $this->filtroDisciplina($disciplina_id, $start, $end, $celula->turno_id, $module_id);
                return $resp->isEmpty();
            endif;
            return false;
        }

    }

    public function filtroDisciplina($disciplina_id, $start, $end, $turno_id, $module_id = null)
    {

        $filtered = $this->celulasBase
            ->filter(function ($celula) use ($disciplina_id, $start, $end, $turno_id, $module_id) {
                $result = $celula->disciplina_id == $disciplina_id &&
                    $celula->turno_id == $turno_id &&
                    $celula->dia >= $start &&
                    $celula->dia <= $end;
                if ($module_id):
                    return $result && $celula->module_id == $module_id;
                endif;
                return $result;
            });
        return $filtered;
    }

    public function filtroAula($aula_id, $start, $end, $turno_id)
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

    public function filtroDiaHorario($dia, $horario)
    {
        $filtered = $this->celulasMarcadas
            ->filter(function ($celula) use ($dia, $horario) {

                return $celula->dia == $dia &&
                    $celula->horario == $horario;
            });
        return $filtered;

    }

    public function isDateFuture($dia, $horario)
    {
        $dateHourNow = date('Y-m-d H:i');
        $dateToCompare = $dia . ' ' . $horario;
        return $dateToCompare >= $dateHourNow;
    }

    public function getSystemCount($disciplina_id)
    {
        $resp = $this->systemCounts->firstWhere('disciplina_id', $disciplina_id);
        if ($resp) {
            return $resp->contador;
        }
        return 1;

    }

    public function mapToEvents($celulas, $background = '#28a745')
    {
        $list = [];
        foreach ($celulas as $celula):
            $key = $celula->dia . " " . $celula->horario;
            $obj = new \stdClass();
            $obj->id = $celula->id;
            $obj->title = '';
            $obj->start = $celula->dia . " " . $celula->horario;
            $obj->backgroundColor = $background;

            if (isset($list[$key])) {
                $list[$key]->teachers[] = $celula->teacher_id;
                $list[$key]->celulas[] = $celula->id;

            } else {
                $obj->teachers = [$celula->teacher_id];
                $obj->celulas = [$celula->id];
                $list[$key] = $obj;

            }
            $countTeachers = count($list[$key]->teachers);
            $list[$key]->title = '(' . $countTeachers . ')';

        endforeach;


        return array_values($list);
    }

    public function isValidOrdemBase($celula)
    {
        $level = $this->levelStudent->getLevel($celula->dia, $celula->horario);
        $ordemAulaTarget = ($this->aulaRequest->ordem - 1);
        return $level >= $ordemAulaTarget;
    }

    public function getAulaRequest()
    {
        return $this->aulaRequest;
    }

    public function getLevelStudent($celula)
    {
        $level = $this->levelStudent->getLevel($celula->dia, $celula->horario);
        return $level;
    }

    public static function filterCelulasByPeriod($celulas, $startString, $endString)
    {
        $filtered = $celulas->filter(function ($celula) use ($startString, $endString) {
            $dateToCompare = $celula->dia . ' ' . $celula->horario;
            return $dateToCompare >= $startString && $dateToCompare <= $endString;
        });
        return $filtered;
    }




}