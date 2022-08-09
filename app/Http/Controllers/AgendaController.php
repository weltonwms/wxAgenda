<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Celula;
use App\Models\Aula;
use App\Helpers\AulaHelper;
use App\Helpers\AgendaHelper;
use Carbon\Carbon;
use App\Models\Systemcount;

class AgendaController extends Controller
{

    public function index()
    {
       
        //$x=\App\Models\Systemcount::run(10);
        /*
        $x=Celula::
            join('horarios', 'horarios.horario', 'celulas.horario')
            ->leftJoin('aulas', 'aulas.id', 'celulas.aula_id')
            ->select('celulas.*', 'horarios.turno_id', 'aulas.disciplina_id')
            ->with('students')
            ->orderBy('celulas.dia')
            ->orderBy('celulas.horario')
            ->get();

        foreach($x as $z):
            echo "celula ".$z->id. " possui ".$z->students->count();
            echo "<br>";
        endforeach;
        dd($x->toArray());
        */

       
        return view('agenda.index');

    }

    public function aulasToAgenda()
    {
        $student=auth()->user()->student;
        $aulaHelper = new AulaHelper();
        $aulaHelper->module_id =$student->module_id;
        $aulaHelper->student_id = $student->id;
        $aulaHelper->start();
        $resp = $aulaHelper->filtrar();
        //dd($resp->toArray());
        return view('agenda.aulasToAgenda', compact('resp'));

    }
    public function teste()
    {
        $modulo_id = 2;
        $student_id = 3;

        $aulaHelper = new AulaHelper();
        $aulaHelper->module_id = 2;
        $aulaHelper->student_id = 3;
        $aulaHelper->start();
        $resp = $aulaHelper->filtrar();
        return view('agenda.index', compact('resp'));
        return response()->json($resp);
        exit();
        $x = Disciplina::with(['aulas' => function ($query) use ($modulo_id) {
            $query->where('module_id', $modulo_id);
        }])->whereHas('aulas', function ($query) use ($modulo_id) {
            $query->where('module_id', $modulo_id);
        })->get();

        //disciplina base => capturar todas as aulas feitas
        $c = Aula::join('disciplinas', 'disciplinas.id', '=', 'aulas.disciplina_id')
            ->join('celulas', 'celulas.aula_id', '=', 'aulas.id')
            ->join('celula_student', 'celula_student.celula_id', '=', 'celulas.id')
            ->select('aulas.*', 'celulas.dia as aula_feita_em')
            ->where('aulas.module_id', $modulo_id)
            ->where('disciplinas.base', 1)
            ->where('celula_student.student_id', $student_id)
            ->get();

        //disciplina base => pegar próxima aula a se fazer;
        $level = $this->getLevelStudent($student_id, null, $modulo_id);
        $aulaN = Aula::where('ordem', $level + 1)->first();
        dd($aulaN->toJson());

        return response()->json($c->toArray());
    }

    public function getLevelStudent($student_id, $dateLimit = null, $modulo_id)
    {
        $query = Aula::join('disciplinas', 'disciplinas.id', '=', 'aulas.disciplina_id')
            ->join('celulas', 'celulas.aula_id', '=', 'aulas.id')
            ->join('celula_student', 'celula_student.celula_id', '=', 'celulas.id')
            ->select('aulas.*', 'celulas.dia as aula_feita_em')
            ->where('aulas.module_id', $modulo_id)
            ->where('disciplinas.base', 1)
            ->where('celula_student.student_id', $student_id);
        if ($dateLimit) {
            $query->where('celulas.dia', '<=', $dateLimit);
        }
        $query->orderBy('aulas.ordem', 'DESC');
        $c = $query->get();

        //dd($c);

        $x = $c->isEmpty() ? 0 : $c[0]->ordem;
        return $x;
    }

    public function getEventsAgenda(Request $request)
    {
       
        \DB::enableQueryLog();
        $student=auth()->user()->student;
        $agendaHelper=new AgendaHelper();
        $agendaHelper->setStart(request('start'));
        $agendaHelper->setEnd(request('end'));
        $agendaHelper->aula_id=request('aula_id');
        $agendaHelper->student_id=$student->id;
        $agendaHelper->module_id=$student->module_id;
       
       

        if(!$agendaHelper->isRequestValid()){
            return []; //impedir de ações desnecessárias
        }

        $agendaHelper->start();
        $celulas= $agendaHelper->filtrar();
        $celulaEvents=$agendaHelper->mapToEvents($celulas);
        //dd(\DB::getQueryLog());
        return response()->json($celulaEvents);
    }

    public function getEventsAgendados(Request $request)
    {
        $student=auth()->user()->student;
        $student_id=$student->id;
        $start=$request->start;
        $end= $request->end;

        $query=Celula::join('celula_student', 'celula_student.celula_id', '=', 'celulas.id')
        ->join('aulas','aulas.id', '=','celulas.aula_id')
        ->select('celulas.*','aulas.sigla')
        ->where('celula_student.student_id', $student_id);

        if($start){
            $query->where('celulas.dia','>=',$start);
        }
        if($end){
            $query->where('celulas.dia','<=',$end);
        }
        $resp= $query->get();
        

        return response()->json($this->mapEventosAgendados($resp));
       
        
    }

    private function mapEventosAgendados($celulas)
    {
        return $celulas->map(function($celula){
            $obj= new \stdClass();
            $obj->id=$celula->id;
            $obj->title=$celula->sigla;
            $obj->start=$celula->dia.' '.$celula->horario;
            $obj->backgroundColor='red';
            return $obj;
        });

    }

    public function store(Request $request)
    {
        //validar se pode
        //ações em créditos
        //ações em systemCount
        $student=auth()->user()->student;
        $student_id = $student->id;
        $celula= Celula::find($request->celula_id);
        if(!$celula->aula_id){
           //abrindo célula para aula 
           $celula->aula_id=$request->aula_id;
           $celula->save();
           //rodar o systemCount 
           Systemcount::run($celula->aula_id);
        }
       $celula->students()->attach($student_id);
        //dd($celula->toArray());
        return response()->json($celula->info());
    }

    public function getDadosToAgenda(Request $request)
    {
        //usar para validar também se for o caso;
        //return response()->json($request->all());
        //var_dump($request->teachers);
        //exit();
        $resp=[];
        if($request->celulas):
        $resp=Celula::join('teachers','celulas.teacher_id','=','teachers.id')
        ->select('celulas.id','celulas.dia','celulas.horario','celulas.teacher_id',
        'teachers.nome as nome_professor')
        ->whereIn('celulas.id',$request->celulas)

        ->get();
        endif;
        return response()->json($resp);

    }


}
