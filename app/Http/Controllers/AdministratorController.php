<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdministratorRequest;
use App\Models\Administrator;
use App\Models\User;


class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $administrators = Administrator::all();
        return view("administrators.index", compact('administrators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrators.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AdministratorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdministratorRequest $request)
    {
       

       $administrator=Administrator::create($request->all());
        User::saveUser($request->all(),$administrator); //gatilho para User;
       \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
       if ($request->input('fechar') == 1):
           return redirect()->route('administrators.index');
       endif;
       return redirect()->route('administrators.edit',$administrator->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function show(Administrator $administrator)
    {
        return $administrator->load('user');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function edit(Administrator $administrator)
    {
       
        return view('administrators.edit', compact('administrator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AdministratorRequest  $request
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function update(AdministratorRequest $request, Administrator $administrator)
    {
        $administrator->update($request->all());
        User::saveUser($request->all(),$administrator); //gatilho para User;
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('administrators.index');
        endif;
        return redirect()->route('administrators.edit',$administrator->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Administrator $administrator)
    {
       $retorno = $administrator->delete();
        if ($retorno):
            User::destroyUser($administrator->user_id); //gatilho para User
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
        endif;
        return redirect()->route('administrators.index');
    }

    public function destroyBath()
    {
       $users_ids=Administrator::whereIn('id',request('ids'))->pluck('user_id');//gatilho para User
       $retorno = Administrator::verifyAndDestroy(request('ids'));
        if ($retorno):
            User::destroyUserBath($users_ids); //gatilho para User
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
        endif;
        return redirect()->route('administrators.index');
    }
}
