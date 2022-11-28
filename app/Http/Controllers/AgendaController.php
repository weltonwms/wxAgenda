<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Celula;
use App\Models\Horario;
use App\Helpers\AulaHelper;
use App\Helpers\AgendaHelper;


class AgendaController extends Controller
{

    public function index()
    {
        $horariosList = Horario::getList()->values(); 
        $student = auth()->user()->student;
        //dd($student->getModules()->pluck('nome','id'));
        return view('agenda.index', compact('horariosList', 'student'));
    }

    public function aulasToAgenda()
    {
        $student = auth()->user()->student;
        $aulaHelper = new AulaHelper();
        $aulaHelper->module_id = request('module_id');
        $aulaHelper->student_id = $student->id;
        $aulaHelper->start();
        $disciplinasWithAulas = $aulaHelper->filtrar();

        return view('agenda.aulasToAgenda', compact('disciplinasWithAulas'));
    }

    public function getEventsAgenda(Request $request)
    {
        // \DB::enableQueryLog();
        $student = auth()->user()->student;
        $agendaHelper = new AgendaHelper();
        $agendaHelper->setStart(request('start'));
        $agendaHelper->setEnd(request('end'));
        $agendaHelper->setAulaRequest(\App\Models\Aula::find(request('aula_id')));
        $agendaHelper->student_id = $student->id;
        $agendaHelper->module_id = request('module_id');

        if (!$agendaHelper->isRequestValid()) {
            return []; //impedir de ações desnecessárias
        }

        $agendaHelper->start();
        $celulas = $agendaHelper->filtrar();
        $celulaEvents = $agendaHelper->mapToEvents($celulas);
        //dd(\DB::getQueryLog());
        return response()->json($celulaEvents);
    }

    public function getEventsAgendados(Request $request)
    {
        $student = auth()->user()->student;
        $events = Celula::getEventsAgendados($student->id, $request->start, $request->end);

        return response()->json($events);
    }

    public function store(Request $request)
    {
        try {
            $celulaInfo=\DB::transaction(function () use ($request) {
                $student = auth()->user()->student;
                $celulaInfo = Celula::storeStudent($student, $request->celula_id, $request->aula_id);
                return $celulaInfo;
            });
            return response()->json($celulaInfo);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


    }

    public function getDadosToAgenda(Request $request)
    {
        //usar para validar também se for o caso;
        $dadosCelulas = Celula::getDadosToAgenda($request->celulas);
        return response()->json($dadosCelulas);

    }

    public function statusAulas()
    {
        $aulas_id=json_decode(request('aulas_id'));
        $start=request('start');
        $end=request('end');         
        $student = auth()->user()->student;
        
        $agendaHelper = new AgendaHelper();
        $agendaHelper->setStart($start);
        $agendaHelper->setEnd($end);
        $agendaHelper->student_id = $student->id;
        $agendaHelper->module_id = request('module_id'); //analisar request do module_id

        $output=[];
        
        $aulas=\App\Models\Aula::whereIn('id', $aulas_id)->with('disciplina')->get();
        $startString= Carbon::createFromDate($start)->format("Y-m-d H:i");
        $endString= Carbon::createFromDate($end)->format("Y-m-d H:i");

        $agendaHelper->start();
        foreach ($aulas as $aula):
            $agendaHelper->setAulaRequest($aula);
            $celulas = $agendaHelper->filtrar();            
            //filtrar novamente com start e end;
            $celulas2=$agendaHelper->filterCelulasByPeriod($celulas,$startString,$endString);
            $output[$aula->id]= $celulas2->count();
        endforeach;

        return response()->json($output);
    }


}
