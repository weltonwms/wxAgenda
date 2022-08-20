<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Celula;
use App\Models\Student;

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
        //verify regras de desmarcação;
        try {
            $student = auth()->user()->student;
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

    private function verifyRegrasDesmarcacao()
    {

    }
}
