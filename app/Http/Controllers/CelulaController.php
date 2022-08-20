<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\CelulaStoreRequest;
use App\Http\Requests\CelulasBathRequest;

use App\Models\Celula;
use App\Models\Teacher;
use App\Models\Horario;

class CelulaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * 
     */
    public function index()
    {
        $teachersList = Teacher::getList();
        $horariosList = Horario::getList()->values();
        return view('celulas.index', compact('teachersList', 'horariosList'));
    }

    public function celulasBath(CelulasBathRequest $request)
    {
        $retorno=Celula::createCelulasBath($request);
        if($retorno):
            \Session::flash('mensagem', ['type' => 'success', 
            'conteudo' => 'Disponibilidade de Células  Criada com Sucesso!', 'larger' => true]);
        endif;
       
        return redirect()->back()->withInput($request->input());
    }



    public function getEventsCelula()
    {
        $mapCelulas=Celula::getEventsCelula(request('start'),request('end'),request('teacher_id'));
        return response()->json($mapCelulas);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    public function store(CelulaStoreRequest $request)
    {
        $request->validarDiaHorario();
        $celula = new Celula();
        $celula->dia = $request->dia;
        $celula->horario = $request->horario;
        $celula->teacher_id = $request->teacher_id;
        $celula->save();


        return response()->json($celula);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Celula  $celula
     * 
     */
    public function show(Celula $celula)
    {
        return response()->json($celula->load('students', 'aula'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Celula  $celula
     * 
     */
    public function destroy(Celula $celula)
    {
        try {
            \DB::transaction(function () use($celula){
                foreach($celula->students as $student):
                    Celula::desmarcarStudent($student,$celula);
                endforeach;
                $celula->delete();
            });
            return response()->json(['message' => 'Célula destruída com sucesso!']);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
}
