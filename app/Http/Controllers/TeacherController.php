<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Requests\TeacherRequest;
use App\Models\User;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *     
     */
    public function index()
    {
        $teachers = Teacher::getAllByFilter();
        return view("teachers.index", compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *     
     */
    public function create()
    {
        $horariosList=\App\Models\Horario::getList();
       
        return view('teachers.create',compact('horariosList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request     
     */
    public function store(TeacherRequest $request)
    {
        $teacher = Teacher::create($request->all());
        User::saveUser($request->all(),$teacher); //gatilho para User;
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
        if ($request->input('fechar') == 1):
            return redirect('teachers');
        endif;
        return redirect()->route('teachers.edit', $teacher->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher     
     */
    public function show(Teacher $teacher)
    {
        return $teacher;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher     
     */
    public function edit(Teacher $teacher)
    {
        $horariosList=\App\Models\Horario::getList();
        return view('teachers.edit', compact('teacher','horariosList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(TeacherRequest $request, Teacher $teacher)
    {
        $teacher->update($request->all());
        User::saveUser($request->all(),$teacher); //gatilho para User;
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('teachers.index');
        endif;
        return redirect()->route('teachers.edit', $teacher->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        $retorno = $teacher->delete();
        if ($retorno):
            User::destroyUser($teacher->user_id); //gatilho para User
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
        endif;
        return redirect()->route('teachers.index');
    }

    public function destroyBath()
    {
        $users_ids=Teacher::whereIn('id',request('ids'))->pluck('user_id');//gatilho para User
        $retorno = Teacher::verifyAndDestroy(request('ids'));
        if ($retorno):
            User::destroyUserBath($users_ids); //gatilho para User
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
        endif;
        return redirect()->route('teachers.index');
    }
}
