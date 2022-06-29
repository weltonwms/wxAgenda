<?php

namespace App\Http\Controllers;

use App\Models\Restriction;
use Illuminate\Http\Request;

class RestrictionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restrictions = Restriction::with('module')->with('disciplina')->get();
        return view("restrictions.index", compact('restrictions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dados = [
            'modulesList'=> \App\Models\Module::getList(),
            'disciplinasList' => \App\Models\Disciplina::getList(),
        ];
        
        return view('restrictions.create', $dados);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restriction = Restriction::create($request->all());
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
        if ($request->input('fechar') == 1):
            return redirect('restrictions');
        endif;
        return redirect()->route('restrictions.edit', $restriction->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restriction  $restriction
     * @return \Illuminate\Http\Response
     */
    public function show(Restriction $restriction)
    {
        return $restriction;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Restriction  $restriction
     * @return \Illuminate\Http\Response
     */
    public function edit(Restriction $restriction)
    {
        $dados = [
            'modulesList'=> \App\Models\Module::getList(),
            'disciplinasList' => \App\Models\Disciplina::getList(),
            'restriction'=>$restriction
            
        ];
       
        return view('restrictions.edit', $dados);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restriction  $restriction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restriction $restriction)
    {
        $restriction->update($request->all());
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('restrictions.index');
        endif;
        return redirect()->route('restrictions.edit', $restriction->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restriction  $restriction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restriction $restriction)
    {
        $retorno = $restriction->delete();
        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
        endif;
        return redirect()->route('restrictions.index');
    }

    public function destroyBath()
    {
        $retorno = Restriction::verifyAndDestroy(request('ids'));
        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
        endif;
        return redirect()->route('restrictions.index');
    }
}
