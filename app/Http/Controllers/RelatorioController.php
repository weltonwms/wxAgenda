<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Celula;
use App\Models\RelatorioTeacher;
use App\Models\RelatorioStudent;
use App\Helpers\RelatorioAndamentoHelper as RelatorioAndamento;


class RelatorioController extends Controller
{
   public function teachers(Request $request)
   {
    $relatorio = new RelatorioTeacher();        
    $result = $request->isMethod('post') ? $relatorio->getRelatorio() : $relatorio;

    $dados = [
        'teachers' => Teacher::getList(),
        'relatorio' => $result,
    ];
    return view("relatorios.teachers", $dados);

   }

   public function students(Request $request)
   {
    $relatorio = new RelatorioStudent();        
    $result = $request->isMethod('post') ? $relatorio->getRelatorio() : $relatorio;
    
    $dados = [
        'students' => Student::getList(),
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
        
        $studentsList = Student::getList();
        $modulesList = \App\Models\Module::getList();
        $teachersList = Teacher::getList();
        $disciplinasList = \App\Models\Disciplina::getList();
        
        return view('relatorios.students2', compact('celulas', 'modulesList', 
        'teachersList', 'disciplinasList','studentsList'));

   }

   public function andamento(Request $request)
   {               
        $modulesList = \App\Models\Module::getList();        
        $disciplinasList = \App\Models\Disciplina::getList();
        $studentsList = Student::getList();
        $requestModuleId= request('module_id');
        $student= null;
        $relatorio = new RelatorioAndamento();
        
        if($request->student_id){
            $student = Student::find($request->student_id);
            if(!$requestModuleId && $student){
                $requestModuleId= $student->module_id;
            }          
            
            
        }
        
        if(!$student && $request->isMethod('post')){
                $request->validate(['module_id' => 'required']);
                $relatorio->start($request->module_id, $request->disciplina_id);
        } 

        return view('relatorios.andamento-students', compact('modulesList', 
        'disciplinasList','relatorio','studentsList','requestModuleId', 'student'));

   }
}
