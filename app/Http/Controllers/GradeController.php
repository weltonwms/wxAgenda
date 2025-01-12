<?php
//Controle Destinado a Student
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GradeStoreRequest;
use App\Http\Requests\FilterAgendaRequest;
use App\Models\Teacher;
use App\Models\Horario;
use App\Models\Celula;
use App\Models\Disciplina;
use App\Models\Aula;
use App\Helpers\FilterAgendaHelper;
use App\Helpers\EscolhaAutomaticaAulaHelper;
use App\Models\ReviewInfo;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{
    public function index()
    {
        $teachersList = Teacher::getListActive();
        $horariosList = Horario::getList()->values();
        return view('grade.index', compact('teachersList', 'horariosList'));
    }

    public function getEventsCelula()
    {
        $student = auth()->user()->student;
        $eventsCelula = Celula::getEventsCelula(request('start'), request('end'), request('teacher_id'));
        $eventsAgendados = Celula::getEventsAgendados($student->id, request('start'), request('end'));
        Celula::markEventsAgendadosToEventsCelula($eventsCelula, $eventsAgendados);
        return response()->json($eventsCelula);
    }

    public function getCelula(Celula $celula)
    {
        $celulaWithStudents = $celula->load('students.module', 'aula','reviewInfo');
        if(auth()->user()->isStudent){
            $this->hideInfoStudents($celulaWithStudents->students,$celula);
        }        
        return response()->json($celulaWithStudents);
    }

    public function getDisciplinasAjax()
    {
        $module_id = request('module_id');
        if ($module_id == 'default') {
            $student = auth()->user()->student;
            $module_id = $student->module_id;
        }
        if ($module_id) {
            $disciplinas = Disciplina::getDisciplinasInModule($module_id);
        } else {
            $disciplinas = Disciplina::all();
        }
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

    /**
     * Obtém as células abertas em um determinado período e turno.
     * O período(start, end) será determinado pelo dia de uma célula passada.
     * O intervalo será conforme configuração global. O turno também será determinado por
     * esse dia da célula.
     * A pesquisa será se por uma aula se parâmetro isBase for verdadeiro ou por 
     * modulo/disciplina se isBase for falso.
     * Parametros do Request:
     * celula_id => referencia para dia e turno a pesquisar
     * isBase => saber se pesquisa por aula ou por modulo/disciplina
     * aula_id => usado se isBase
     * disciplina_id => usado se não isBase 
     * module_id => usado se não isBase
     */
    public function getCelulasJaAbertasByTurno(FilterAgendaRequest $request)
    {
        $celula = Celula::findOrFail($request->celula_id);
        $turno_id = $celula->horarioObj->turno_id;

        $start = FilterAgendaHelper::getDayIntervalBefore($celula->dia);
        $end = FilterAgendaHelper::getDayIntervalAfter($celula->dia);

        $helper = new FilterAgendaHelper($start, $end);
        $resposta = new \stdClass();
        if ($request->isBase) {
            //fazer filtro por aula
            $resposta->tipo = 'aula';
            $resposta->list = $helper->filtroAula($request->aula_id, $turno_id);
        } else {
            //fazer filtro por disciplina
            $disciplina_id = $request->disciplina_id;
            $modulo_id = $request->module_id;
            $resposta->tipo = 'disciplina';
            $resposta->list = $helper->filtroDisciplina($disciplina_id, $turno_id, $modulo_id);
        }
        return response()->json($resposta);
    }

    public function storeAgenda(GradeStoreRequest $request)
    {
        try {
            $celulaInfo = \DB::transaction(function () use ($request) {
                $student = auth()->user()->student;
                $aula_id = EscolhaAutomaticaAulaHelper::run($student, $request);
                $reviewInfo = new ReviewInfo();
                $reviewInfo->setAll($request,$student); //info extra aula review
                $reviewInfo->verify(); //validar campos obrigratórios antes de salvar
                $celulaInfo = Celula::storeStudent($student, $request->celula_id, $aula_id, $request->aula_individual,$reviewInfo);
                return $celulaInfo;
                //return response()->json($request->all());

            });
            return response()->json($celulaInfo);
        } 
        catch (QueryException $e){
            $sql = $e->getSql();
            $bindings = json_encode($e->getBindings());
            Log::error("Erro na query Store Agenda: {$e->getMessage()}, SQL: {$sql}, Bindings: {$bindings}");
            return response()->json(['error' => "Erro em Query Banco de Dados"],500);
           
        }
        catch (\Exception $e) {
            $statusCode = 500;
            if($e->getCode() >=400 && $e->getCode()<600){
                $statusCode = $e->getCode();
            }            
            return response()->json(['error' => $e->getMessage()], $statusCode);
        }

    }


    /**
     * Proteger Informações de notas e feedback de colegas. Informação somente para o aluno autenticado
     * Proteger também o link da aula em aulas individuais.
     */
    private function hideInfoStudents($students, $celula)
    {
        $authStudent = auth()->user()->student;
        foreach ($students as $student) {
            if ($student->pivot && $student->id != $authStudent->id) {
                $student->pivot->n1 = '';
                $student->pivot->n2 = '';
                $student->pivot->n3 = '';
                $student->pivot->n4 = '';
                $student->pivot->feedback = '';
            }
        }
        //Se for aula individual e nessa aula não conter o aluno autenticado, esconder aula_link
        if($celula->aula_individual && !$students->contains('id',$authStudent->id)){
           $celula->aula_link='';
        }
    }

    public function getPivotsByStudentAndAula(Request $request)
    {
        $student_id = $request->student_id;
        $aula_id = $request->aula_id;
        $result = Celula::getPivotsByStudentAndAula($student_id,$aula_id);
        return response()->json($result);
    }

}