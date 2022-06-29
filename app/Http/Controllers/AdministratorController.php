<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdministratorRequest;
use App\Models\Administrator;

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function show(Administrator $administrator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function edit(Administrator $administrator)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Administrator $administrator)
    {
        //
    }

    public function destroyBath()
    {
        $retorno = Administrator::verifyAndDestroy(request('ids'));
        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
        endif;
        return redirect()->route('administrators.index');
    }
}
