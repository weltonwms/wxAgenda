<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GradeStoreRequest;
use App\Models\Teacher;
use App\Models\Horario;
use App\Models\Celula;
use App\Models\Module;
use App\Models\Disciplina;
use App\Models\Aula;
use App\Models\Systemcount;

class GradeController extends Controller
{
    public function index()
    {
        $teachersList = Teacher::getList();
        $horariosList = Horario::getList()->values();
        return view('grade.index', compact('teachersList', 'horariosList'));
    }

    public function getEventsCelula()
    {
        $mapCelulas = Celula::getEventsCelula(request('start'), request('end'), request('teacher_id'));
        return response()->json($mapCelulas);
    }

    public function getCelula(Celula $celula)
    {
        return response()->json($celula->load('students', 'aula'));
    }

    public function getDisciplinasAjax()
    {
        $disciplinas = Disciplina::all();
        return response()->json($disciplinas);
    }

    public function getModulesAjax()
    {
        $student = auth()->user()->student;
        $obj = new \stdClass();
        $obj->current_module = $student->module_id;
        $obj->modules = $student->getModules();
        return response()->json($obj);
    }

    public function getAulasAjax(Request $request)
    {
        $aulas = Aula::where('disciplina_id', $request->disciplina_id)
            ->where('module_id', $request->module_id)->get();
        return response()->json($aulas);
    }

    public function storeAgenda(GradeStoreRequest $request)
    {
        try {
            $celulaInfo = \DB::transaction(function () use ($request) {
                $student = auth()->user()->student;
                $aula_id = $this->getAulaIdForAgenda($request);
                $celulaInfo = Celula::storeStudent($student, $request->celula_id, $aula_id);
                return $celulaInfo;

            });
            return response()->json($celulaInfo);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ? $e->getCode() : 500;
            return response()->json(['error' => $e->getMessage()], $statusCode);
        }

    }

    private function getAulaIdForAgenda($request)
    {
        if ($request->aula_id):
            return $request->aula_id;
        endif;

        if (!$request->disciplina_id):
            throw new \Exception('Seleção de Disciplina Obrigatória!', 422);
        endif;

        $disciplina = Disciplina::find($request->disciplina_id);
        if ($disciplina->base):
            throw new \Exception('Seleção de Aula Obrigatória!', 422);
        endif;

        if (!$request->module_id):
            throw new \Exception('Seleção de Módulo Obrigatória!', 422);
        endif;

        $ordem = Systemcount::getContador($request->module_id, $request->disciplina_id);
        $aula = Aula::where('module_id', $request->module_id)
            ->where('disciplina_id', $request->disciplina_id)
            ->where('ordem', $ordem)->first();

        if (!$aula):
            throw new \Exception('Erro ao Encontrar Nº Aula!');
        endif;

        return $aula->id;
    }


}