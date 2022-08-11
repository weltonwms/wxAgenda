<?php
namespace App\Helpers;
use App\Models\Celula;

class LevelStudentHelper
{
    private $aulasBasesFeitas;

    public function __construct($student_id, $module_id)
    {
        $this->start($student_id, $module_id);
    }

    public function start($student_id, $module_id)
    {
        $this->aulasBasesFeitas = Celula::join('celula_student', 'celula_student.celula_id', '=', 'celulas.id')
            ->join('aulas', 'aulas.id', 'celulas.aula_id')
            ->join('disciplinas', 'aulas.disciplina_id', 'disciplinas.id')
            ->select('celula_id', 'celulas.horario', 'celulas.dia',
            \DB::raw("CONCAT(celulas.dia,' ',TIME_FORMAT(celulas.horario,'%H:%i')) as dia_horario"),
            'celulas.aula_id', 'aulas.ordem')
            ->where('celula_student.student_id', $student_id)
            ->where('aulas.module_id', $module_id)
            ->where('disciplinas.base', 1)
            ->get();

    }


    public function getLevel($dia, $horario)
    {
        $aulasBasesFeitas = $this->aulasBasesFeitas;
        $dia_horario = $dia . ' ' . $horario;
        $ultimaAulaBaseFeita = $aulasBasesFeitas
            ->where('dia_horario', '<=', $dia_horario)
            ->sortByDesc('ordem')
            ->first();
        return $ultimaAulaBaseFeita ? $ultimaAulaBaseFeita->ordem : 0;

    }
}