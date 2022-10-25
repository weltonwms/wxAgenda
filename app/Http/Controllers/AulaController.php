<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use Illuminate\Http\Request;
use App\Http\Requests\AulaRequest;

class AulaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $aulas = Aula::getAulasByFiltro();
        $modulesList = \App\Models\Module::getList();
        $disciplinasList = \App\Models\Disciplina::getList();
        return view("aulas.index", compact('aulas','modulesList','disciplinasList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $dados = [
            'modulesList'=> \App\Models\Module::getList(),
            'disciplinasList' => \App\Models\Disciplina::getList(),
        ];
        
        return view('aulas.create', $dados);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(AulaRequest $request)
    {
        $aula = Aula::create($request->all());
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
        if ($request->input('fechar') == 1):
            return redirect('aulas');
        endif;
        return redirect()->route('aulas.edit', $aula->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Aula  $aula
     */
    public function show(Aula $aula)
    {
       return $aula;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Aula  $aula
     */
    public function edit(Aula $aula)
    {
        $dados = [
            'modulesList'=> \App\Models\Module::getList(),
            'disciplinasList' => \App\Models\Disciplina::getList(),
            'aula'=>$aula
            
        ];
       
        return view('aulas.edit', $dados);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Aula  $aula
     * @return \Illuminate\Http\Response
     */
    public function update(AulaRequest $request, Aula $aula)
    {
        $aula->update($request->all());
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('aulas.index');
        endif;
        return redirect()->route('aulas.edit', $aula->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Aula  $aula
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aula $aula)
    {
        $retorno = $aula->delete();
        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
        endif;
        return redirect()->route('aulas.index');
    }

    public function destroyBath()
    {
        $retorno = Aula::verifyAndDestroy(request('ids'));
        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
        endif;
        return redirect()->route('aulas.index');
    }

    public function getAulasAjax(Request $request)
    {
        $aulas = Aula::where('disciplina_id',$request->disciplina_id)
        ->where('module_id',$request->module_id)->get();
        return response()->json($aulas);
    }

    public function showSystemCounter()
    {
        $registros=\App\Models\Systemcount::with('module')->with('disciplina')->get();
        return view('aulas.counter',compact('registros'));
    }
}
