<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Celula;
use App\Models\Student;
use App\Helpers\ConfiguracoesHelper;


class AgendadosController extends Controller
{
    public function index()
    {        
       
        $student = auth()->user()->student;
        $celulas = Celula::getCelulasAgendadas($student);
        $modulesList = \App\Models\Module::getList();
        $teachersList = \App\Models\Teacher::getList();
        $disciplinasList = \App\Models\Disciplina::getList();
        $limitDesmarcacao=ConfiguracoesHelper::desmarcacao_limit_by_month();
        return view('agenda.agendados', compact('celulas', 'modulesList', 
        'teachersList', 'disciplinasList','student','limitDesmarcacao'));
    }



    public function desmarcar(Celula $celula)
    {
        
        try {
            $student = auth()->user()->student;
            $this->verifyRegrasDesmarcacao($student,$celula);
            $msg= 'Aula Desmarcada com Sucesso!<br>';
            \DB::transaction(function () use($celula, $student,&$msg){               
                $retorno=Celula::desmarcarStudent($student,$celula); 
               
                $msg.= $retorno['credit_provided']?'<strong>Crédito Devolvido!</strong>':
                '<strong class="text-danger">Crédito não Devolvido!(verifique o limite de desmarcações no mês)</strong>';
            });
            if(request()->ajax()){
                return response()->json(["message"=>$msg]);
            }
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => $msg]);
            return redirect()->route('agendados.index');
        }
        catch (\Exception $e) {
            if(request()->ajax()){
                return response()->json(["error"=>$e->getMessage()],400);
            }
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
            return redirect()->route('agendados.index');
        }
       

    }

    private function verifyRegrasDesmarcacao($student,$celula)
    {
        $desmarcarcaoPermitida=ConfiguracoesHelper::desmarcacao_permitida();
        if(!$desmarcarcaoPermitida){
            throw new \Exception("Desmarcação Bloqueada por Administrador");
       }

        if(!$celula->isOnLimitHoursToStart()){
            $limitHoursToStart=ConfiguracoesHelper::desmarcacao_hours_before();
             throw new \Exception("Desmarcação deverá ser feita com antecedência de ".$limitHoursToStart."h");
        }
        //Não proibir mais o aluno de desmarcar
        /*
        if(!$student->isOnLimitCancellationsByMonth()){
            throw new \Exception("Limite Estourado de Desmarcações por mês ");
        }
        */

    }
}
