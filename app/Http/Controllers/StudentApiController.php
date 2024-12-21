<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentApiController extends Controller
{
    /**
    * Lista do Alunos com direitos a desconto.
     * Somente com CPF e alunos ativos
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getDiscount()
    {
        $students = Student::where('active',1)->where(function($query) {
            $query->whereNotNull('cpf')
                  ->where('cpf', '!=', '');
        })
        ->get()
        ->map(function($student){
            return [
                "id"=>$student->id,
                "nome"=>$student->nome,
                "cpf"=>$student->cpf,
                "email"=>$student->email,
                "telefone"=>$student->telefone,
                "status" => "ativo"
            ];
        });
        return response()->json($students);
    }

    /**
     * Retorna lista de todos alunos ativos no sistema
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getActives()
    {
        $students = Student::select('id','nome','cpf','email','telefone','active','cidade','uf','saldo_atual')
                ->where('active',1)
                ->get();
        return response()->json($students);

    }
   

}
