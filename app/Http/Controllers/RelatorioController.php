<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
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
}
