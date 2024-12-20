<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PerfilRequest;
use App\Http\Requests\PerfilStudentRequest;

class PerfilController extends Controller
{
    public function showEdit()
    {
        return view('perfil.master');
    }

    public function updatePassword(PerfilRequest $request)
    {

        $user = \Auth::user();
        $new_password = $request->input('password');
        $user->password = \Hash::make($new_password);
        $user->save();
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('Senha Alterada com Sucesso!')]);
        return back();
    }

    public function updateDadosStudent(PerfilStudentRequest $request)
    {
        $user = \Auth::user();
        $student = $user->student;
        $student->nome = $request->input('nome');
        $student->cpf = $request->input('cpf');
        $student->telefone = $request->input('telefone');       
        $student->save();
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('Dados Salvos com Sucesso!')]);
        return back();

    }

    public function getAuthStudent()
    {
        $student=auth()->user()->student;
        if($student){
            $student->load('module');
        }
        return response()->json($student);
    }

    public function getAulasAgendadas(Request $request)
    {
        $student=auth()->user()->student;
        if($student){
            $resp=$student->getAulasAgendadas($request->module_id,$request->disciplina_id);
            return response()->json($resp); 
        }
        return response()->json([]);
    }
}
