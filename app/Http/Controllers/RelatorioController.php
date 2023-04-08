<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Celula;
use App\Models\RelatorioTeacher;
use App\Models\RelatorioStudent;


class RelatorioController extends Controller
{
   public function teachers(Request $request)
   {
    $relatorio = new RelatorioTeacher();        
    $result = $request->isMethod('post') ? $relatorio->getRelatorio() : $relatorio;

    $dados = [
        'teachers' => Teacher::pluck('nome', 'id'),
        'relatorio' => $result,
    ];
    return view("relatorios.teachers", $dados);

   }

   public function students(Request $request)
   {
    $relatorio = new RelatorioStudent();        
    $result = $request->isMethod('post') ? $relatorio->getRelatorio() : $relatorio;
    
    $dados = [
        'students' => Student::pluck('nome', 'id'),
        'relatorio' => $result,
    ];
    return view("relatorios.students", $dados);

   }

   public function students2(Request $request)
   { 
        if($request->isMethod('post')){
            $request->validate(['student_id' => 'required']);
        }        
        $celulas=[];
        if($request->student_id):
            $student=Student::find($request->student_id);
            $celulas = Celula::getCelulasAgendadas($student);
        endif;
        
        $studentsList = \App\Models\Student::getList();
        $modulesList = \App\Models\Module::getList();
        $teachersList = \App\Models\Teacher::getList();
        $disciplinasList = \App\Models\Disciplina::getList();
        
        return view('relatorios.students2', compact('celulas', 'modulesList', 
        'teachersList', 'disciplinasList','studentsList'));

   }
}
