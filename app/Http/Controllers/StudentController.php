<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Requests\StudentRequest;
use App\Models\User;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *     
     */
    public function index()
    {
        //\DB::enableQueryLog();
        //use \DB::getQueryLog() no ponto da view que deseja;
        $students = Student::with('module')->get();
        return view("students.index", compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *     
     */
    public function create()
    {
        $dados=[
            'modulesList'=> \App\Models\Module::getList(),
        ];
        return view('students.create',$dados);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request     
     */
    public function store(StudentRequest $request)
    {
        $student = Student::create($request->all());
        User::saveUser($request->all(),$student); //gatilho para User;
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
        if ($request->input('fechar') == 1):
            return redirect('students');
        endif;
        return redirect()->route('students.edit', $student->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student     
     */
    public function show(Student $student)
    {
        return $student->load('user');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student     
     */
    public function edit(Student $student)
    {
        $modulesList= \App\Models\Module::getList();
        return view('students.edit', compact('student','modulesList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, Student $student)
    {
        $student->update($request->all());
        User::saveUser($request->all(),$student); //gatilho para User;
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);
        if ($request->input('fechar') == 1):
            return redirect()->route('students.index');
        endif;
        return redirect()->route('students.edit', $student->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $retorno = $student->delete();
        if ($retorno):
            User::destroyUser($student->user_id); //gatilho para User
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
        endif;
        return redirect()->route('students.index');
    }

    public function destroyBath()
    {
        $users_ids=Student::whereIn('id',request('ids'))->pluck('user_id');//gatilho para User
        $retorno = Student::verifyAndDestroy(request('ids'));
        if ($retorno):
            User::destroyUserBath($users_ids); //gatilho para User
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
        endif;
        return redirect()->route('students.index');
    }

    public function getStudentsAjax()
    {
        $students=Student::all();
        return response()->json($students);
    }
}
