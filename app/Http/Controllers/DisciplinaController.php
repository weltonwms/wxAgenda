<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use Illuminate\Http\Request;
use App\Http\Requests\DisciplinaRequest;

class DisciplinaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $disciplinas = Disciplina::all();
        return view("disciplinas.index", compact('disciplinas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('disciplinas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(DisciplinaRequest $request)
    {
        $disciplina = Disciplina::create($request->all());
        Disciplina::updateBases($disciplina); 
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
        if ($request->input('fechar') == 1):
            return redirect('disciplinas');
        endif;
        return redirect()->route('disciplinas.edit', $disciplina->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Disciplina  $disciplina
     */
    public function show(Disciplina $disciplina)
    {
        return $disciplina;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Disciplina  $disciplina
     */
    public function edit(Disciplina $disciplina)
    {
        return view('disciplinas.edit', compact('disciplina'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Http\Response
     */
    public function update(DisciplinaRequest $request, Disciplina $disciplina)
    {
        $disciplina->update($request->all());
        Disciplina::updateBases($disciplina); 
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('disciplinas.index');
        endif;
        return redirect()->route('disciplinas.edit', $disciplina->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disciplina $disciplina)
    {
        $retorno = $disciplina->verifyAndDelete();
        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
        endif;
        return redirect()->route('disciplinas.index');
    }

    public function destroyBath()
    {
        $retorno = Disciplina::verifyAndDestroy(request('ids'));
        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
        endif;
        return redirect()->route('disciplinas.index');
    }

    public function getDisciplinasAjax()
    {
        $disciplinas = Disciplina::all();
        return response()->json($disciplinas);
    }
}
