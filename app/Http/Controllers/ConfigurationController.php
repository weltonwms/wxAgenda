<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
        $stringConfig = file_get_contents(config_path('agenda.php') );
        $configuracoes = eval("?>".$stringConfig);
        
       
        return view('configurations.index', compact('configuracoes'));
    }

    public function save(Request $request)
    {
       
        \Config::write('agenda.agendamento_ativo', $request->agendamento_ativo);        
        \Config::write('agenda.celula_limit', $request->celula_limit);
        \Config::write('agenda.desmarcacao_limit_by_month', $request->desmarcacao_limit_by_month);
        \Config::write('agenda.desmarcacao_hours_before', $request->desmarcacao_hours_before);
        \Config::write('agenda.desmarcacao_permitida', $request->desmarcacao_permitida);
        
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => 'Configurações Salvas com Sucesso!']);      
        return redirect('configurations');
    }
}
