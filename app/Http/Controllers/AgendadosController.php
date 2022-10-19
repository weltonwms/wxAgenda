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
        return view('agenda.agendados', compact('celulas', 'modulesList', 'teachersList', 'disciplinasList','student'));
    }



    public function desmarcar(Celula $celula)
    {
        try {
            $student = auth()->user()->student;
            $this->verifyRegrasDesmarcacao($student,$celula);
            \DB::transaction(function () use($celula, $student){               
                Celula::desmarcarStudent($student,$celula);               
            });
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => 'Aula Desmarcada com Sucesso!']);
            return redirect()->route('agendados.index');
        }
        catch (\Exception $e) {
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
             throw new \Exception("Desmarcação deverá ser feita com antecedência");
        }
        //Não proibir mais o aluno de desmarcar
        /*
        if(!$student->isOnLimitCancellationsByMonth()){
            throw new \Exception("Limite Estourado de Desmarcações por mês ");
        }
        */

    }
}
