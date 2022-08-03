<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Celula;
use App\Models\Student;

class AgendadosController extends Controller
{
    public function index()
    {
       $celulas= $this->getCelulasAgendadas();
       $modulesList= \App\Models\Module::getList();
       $teachersList= \App\Models\Teacher::getList();
       $disciplinasList= \App\Models\Disciplina::getList();
       return view('agenda.agendados',compact('celulas','modulesList','teachersList','disciplinasList'));
    }

    private function getCelulasAgendadas()
    {
        
        $student_id=3;
        $module_id=request('module_id');
        $disciplina_id=request('disciplina_id');
        $teacher_id=request('teacher_id');
        $start=request('start');
        $end=request('end');
       $query=Celula::join('celula_student','celulas.id','=','celula_student.celula_id')
       ->join('aulas','celulas.aula_id','=','aulas.id')
       ->join('disciplinas','aulas.disciplina_id','=','disciplinas.id')
       ->join('modules','aulas.module_id','=','modules.id')
       ->join('teachers','celulas.teacher_id','=','teachers.id')
       ->select('celulas.id', 'celulas.dia','celulas.horario','celulas.aula_id','celulas.teacher_id',
       'aulas.sigla as aula_sigla','aulas.module_id','aulas.disciplina_id',
       'disciplinas.nome as disciplina_nome','modules.nome as module_nome',
       'teachers.nome as teacher_nome')
       ->where('celula_student.student_id',$student_id);

       if($disciplina_id){
            $query->where('aulas.disciplina_id',$disciplina_id);
       }

       if($module_id){
        $query->where('aulas.module_id',$module_id);
       }
       
       if($teacher_id){
        $query->where('celulas.teacher_id',$teacher_id);
       }

       if($start){
        $query->where('celulas.dia','>=',$start);
       }
       if($end){
        $query->where('celulas.dia','<=',$end);
       }
       $result=$query->orderBy('celulas.dia')->orderBy('celulas.horario')->get();
       return $result;
    }
}
