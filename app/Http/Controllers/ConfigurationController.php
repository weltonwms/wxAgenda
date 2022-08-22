<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
       
        return view('configurations.index');
    }

    public function save(Request $request)
    {
       
        \Config::write('agenda.agendamento_ativo', $request->agendamento_ativo);
        \Config::write('agenda.celula_limit', $request->celula_limit);
        \Config::write('agenda.desmarcacao_limit_by_month', $request->desmarcacao_limit_by_month);
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('Configurações Salvas com Sucesso!')]);
        return back();
    }
}
