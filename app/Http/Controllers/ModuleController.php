<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Requests\ModuleRequest;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $modules = Module::all();
        return view("modules.index", compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('modules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(ModuleRequest $request)
    {
        $module = Module::create($request->all());
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
        if ($request->input('fechar') == 1):
            return redirect('modules');
        endif;
        return redirect()->route('modules.edit', $module->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Module  $module
     */
    public function show(Module $module)
    {
        return $module;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Module  $module
     */
    public function edit(Module $module)
    {
        return view('modules.edit', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(ModuleRequest $request, Module $module)
    {
        $module->update($request->all());
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('modules.index');
        endif;
        return redirect()->route('modules.edit', $module->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        $retorno = $module->verifyAndDelete();
        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
        endif;
        return redirect()->route('modules.index');
    }

    public function destroyBath()
    {
        $retorno = Module::verifyAndDestroy(request('ids'));
        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
        endif;
        return redirect()->route('modules.index');
    }

    public function getModulesAjax()
    {
        $modules=Module::all();
        return response()->json($modules);
    }
}
