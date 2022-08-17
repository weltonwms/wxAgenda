<?php

namespace App\Http\Controllers;

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
        return view('agenda.index', compact('horariosList'));
    }

    public function aulasToAgenda()
    {
        $student = auth()->user()->student;
        $aulaHelper = new AulaHelper();
        $aulaHelper->module_id = $student->module_id;
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
        $agendaHelper->aula_id = request('aula_id');
        $agendaHelper->student_id = $student->id;
        $agendaHelper->module_id = $student->module_id;

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


}
