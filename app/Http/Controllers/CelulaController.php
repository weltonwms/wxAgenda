<?php

namespace App\Http\Controllers;

use App\Models\Celula;
use Illuminate\Http\Request;

use App\Models\Teacher;
use App\Models\Horario;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CelulaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * 
     */
    public function index()
    {
        $teachersList=Teacher::getList();
        $horariosList=Horario::getList()->values();
        return view('celulas.index',compact('teachersList','horariosList'));
    }

    public function celulasBath(Request $request)
    {
        //dd($request->all());
        $teacher = Teacher::find($request->teacher_id);

        if (!$teacher->disponibilidade) {
            dd('Não é possível construir sem o padrão de disponibilidade');
        }

        $disponibilidade = json_decode($teacher->disponibilidade, true);

        $period = CarbonPeriod::create($request->periodo_inicio, $request->periodo_fim);
        $inserts = [];
        foreach ($period as $date) {
            $dayOfWeek = $date->dayOfWeek;
            foreach ($disponibilidade[$dayOfWeek] as $horario) {
                $inserts[] = ["horario" => $horario, "dia" => $date->format('Y-m-d'), "teacher_id" => $teacher->id];
            }

        //echo "<br><br>";
        //echo $date->format('d-m-Y')."   ".$date->englishDayOfWeek."  ".$date->dayOfWeek;
        }

       /*
        $remover=[
            ["horario"=>"10:00","dia"=>"2022-07-12","teacher_id"=>1],
            ["horario"=>"11:00","dia"=>"2022-07-12","teacher_id"=>1],
            ["horario"=>"13:00","dia"=>"2022-07-12","teacher_id"=>1],
            ["horario"=>"09:00","dia"=>"2022-07-30","teacher_id"=>1],
        ];
        */

        $celulasExistentes=Celula::select('horario','dia','teacher_id')->get()->toArray();
        $resultado= array_udiff($inserts,$celulasExistentes,function($a,$b){
            $stringA=json_encode($a);
            $stringB=json_encode($b);
            return $stringA <=> $stringB;

        });

        \DB::table('celulas')->insert($resultado);
        //echo "<pre>";
        //print_r($resultado);
        //print_r($inserts);
        //dd($disponibilidade);
        
        return redirect()->back()->withInput($request->input());
    }

    

    public function getEventsCelula()
    {
        $start=Carbon::createFromDate(request('start'))->format('Y-m-d');
        $end=Carbon::createFromDate(request('end'))->format('Y-m-d');
        $teacher_id=request('teacher_id');
        $celulas=Celula::where('dia','>=',$start)->where('dia','<=',$end)
            ->where('teacher_id',$teacher_id)
            ->withCount('students')
            ->with('aula')
            ->get();

           //dd($celulas->toArray());
        $mapCelulas=$celulas->map(function($celula){
            $obj=new \stdClass();
            $obj->id=$celula->id;
            $title='';
            
            if($celula->aula && $celula->aula->sigla){
                $title= $celula->aula->sigla." (".$celula->students_count.")";
            }
            $obj->title=$title;
            $obj->start=$celula->dia." ".$celula->horario;
            $obj->teacher_id=$celula->teacher_id;
            if($celula->students_count){
                $obj->backgroundColor='red';
            }
            if($celula->students_count>3){
                $obj->backgroundColor='#6c757d';
            }
            return $obj;
        });

        return response()->json($mapCelulas);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    public function store(Request $request)
    {
        $celula= new Celula();
       // $start=Carbon::createFromDate(request('start'));
       $celula->dia=$request->dia;
       $celula->horario=$request->horario;
       $celula->teacher_id=$request->teacher_id;
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
        return response()->json($celula->load('students','aula'));
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Celula  $celula
     * 
     */
    public function destroy(Celula $celula)
    {
        //destruir as celula_student e adicionar créditos aos alunos
        $celula->students()->detach();

        //destruir a célula
        $celula->delete();
        return response()->json(['mensage'=>'Deletado com sucesso!']);
    }
}
