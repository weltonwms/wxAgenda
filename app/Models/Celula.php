<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Systemcount;
use App\Helpers\StoreStudentHelper;
use App\Helpers\ConfiguracoesHelper;
use App\Helpers\TelegramHelper;
use App\Mail\NotificacaoAluno;
use Illuminate\Support\Facades\Mail;
use App\Models\ReviewInfo;
use App\Events\AulaAgendada;
use App\Events\AulaDesmarcada;
use App\Events\EntryAulaOnCelula;
use App\Events\ExitAulaOnCelula;

class Celula extends Model
{
    use HasFactory;


    public function students()
    {
        return $this->belongsToMany(Student::class)
        ->withPivot('presenca', 'n1','n2','n3','n4','feedback')
        ->withTimestamps();

    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function reviewInfo()
    {
        return $this->hasOne(ReviewInfo::class);
    }

    public function getHorarioAttribute($value)
    {
        $date = new \DateTime($value);
        return $date->format("H:i");
    }

    public function horarioObj()
    {
        return $this->belongsTo(Horario::class,'horario');   
    }

    public function getDiaFormatado()
    {
        if ($this->dia):
            //evitando fazer um parse em nada. Não seria necessário se campo fosse obrigatório
            return Carbon::parse($this->dia)->format('d/m/Y');
        //return Carbon::parse($value)->format('Y-m-d');
        endif;
        return '';
    }

    public function info()
    {
        $info = new \stdClass();
        $info->id = $this->id;
        $info->horario = $this->horario;
        $info->dia = $this->getDiaFormatado();
        $info->teacher = $this->teacher->nome;

        return $info;
    }

    public static function createCelulasBath($request)
    {
        $teacher = Teacher::find($request->teacher_id);
        if (!$teacher->disponibilidade) {
            \Session::flash('mensagem', ['type' => 'danger', 'larger' => true,
                'conteudo' => 'Não é possível construir sem o padrão de disponibilidade']);
            return false;
        }

        $disponibilidade = json_decode($teacher->disponibilidade, true);
        $period = CarbonPeriod::create($request->periodo_inicio, $request->periodo_fim);
        $inserts = [];
        $dateHourNow=date('Y-m-d H:i');
        foreach ($period as $date) {
            $dayOfWeek = $date->dayOfWeek;
            foreach ($disponibilidade[$dayOfWeek] as $horario) {
                $dateHourCompare=$date->format('Y-m-d').' '.$horario;
               //Condição para incluir somente dateHour Futura. Retirar condição caso queira criar no passado.
                if($dateHourCompare>=$dateHourNow):
                    $inserts[] = ["horario" => $horario, "dia" => $date->format('Y-m-d'), "teacher_id" => $teacher->id];
                endif;
            }
        }
       
        //removendo células existentes dos inserts. array_udiff retorna a diferença.
        //Importante para não inserir células que já existem.
        $celulasExistentes = Celula::select('horario', 'dia', 'teacher_id')->get()->toArray();
        $resultado = array_udiff($inserts, $celulasExistentes, function ($a, $b) {
            $stringA = json_encode($a);
            $stringB = json_encode($b);
            return $stringA <=> $stringB;
        });
        
        return \DB::table('celulas')->insert($resultado);

    }

    public static function getEventsCelula($start,$end,$teacher_id)
    {
        $start = Carbon::createFromDate($start)->format('Y-m-d');
        $end = Carbon::createFromDate($end)->format('Y-m-d');
        $celula_limit= ConfiguracoesHelper::celula_limit();

        $celulas = Celula::where('dia', '>=', $start)->where('dia', '<=', $end)
            ->where('teacher_id', $teacher_id)
            ->withCount('students')
            ->with('aula')
            ->get();

        
        $mapCelulas = $celulas->map(function ($celula) use($celula_limit){
            $obj = new \stdClass();
            $obj->id = $celula->id;
            $title = '';

            if ($celula->aula && $celula->aula->sigla) {
                $title = $celula->aula->sigla . " (" . $celula->students_count . ")";
            }
            $obj->title = $title;
            $obj->start = $celula->dia . " " . $celula->horario;
            $obj->teacher_id = $celula->teacher_id;
            $obj->backgroundColor = '#28a745';
            $obj->textColor='#fff';

            if ($celula->students_count) {
                $obj->backgroundColor = '#ffc107';
                $obj->textColor='#343a40';
            }
            
            if ($celula->students_count >= $celula_limit) {
                $obj->backgroundColor = '#dc3545';
                $obj->textColor='#fff';
            }
            if($celula->aula_individual):
                //Para aulas individuais não importa o limite configurado, sempre o limite será de um aluno
                //repetir cores de limite estourado ou colocar outra cor
                $obj->backgroundColor = '#dc3545';
                $obj->textColor='#fff';               
            endif;
            return $obj;
        });
        return $mapCelulas;

    }

    public static function getEventsAgendados($student_id, $start = null, $end = null)
    {
        $query = Celula::join('celula_student', 'celula_student.celula_id', '=', 'celulas.id')
            ->join('aulas', 'aulas.id', '=', 'celulas.aula_id')
            ->select('celulas.*', 'aulas.sigla')
            ->where('celula_student.student_id', $student_id);

        if ($start) {
            $query->where('celulas.dia', '>=', $start);
        }
        if ($end) {
            $query->where('celulas.dia', '<=', $end);
        }
        $celulas = $query->get();
        return Celula::mapEventosAgendados($celulas);
    }

    public static function mapEventosAgendados($celulas, $background = 'red')
    {
        return $celulas->map(function ($celula) use ($background) {
            $obj = new \stdClass();
            $obj->id = $celula->id;
            $obj->title = $celula->sigla;
            $obj->start = $celula->dia . ' ' . $celula->horario;
            $obj->backgroundColor = $background;
            return $obj;
        });

    }

    public static function markEventsAgendadosToEventsCelula($eventsCelula,$eventsAgendados)
    {
        foreach($eventsCelula as $eventCelula){
            if($eventsAgendados->contains('id',$eventCelula->id)){
               // $eventCelula->borderColor='#4aefe4';
                $eventCelula->classNames=['eventAuthStudent'];
            }            
        }
        return $eventsCelula;
    }

    public static function storeStudent($student, $celula_id, $aula_id,$aula_individual=0,$reviewInfo=null)
    {
        //validar se pode
        //ações em créditos
        //ações em systemCount

        $celula = Celula::find($celula_id);
        $aulaRequest=Aula::find($aula_id);
        $helper= new StoreStudentHelper();
        $helper->validarStore($student,$celula,$aulaRequest);
        
        if (!$celula->aula_id) {
            //abrindo célula para aula 
            $celula->aula_id = $aula_id;
            $celula->aula_level=$helper->getLevelStudent($celula);
            $celula->aula_individual=$aula_individual;
            $celula->save();
            if($reviewInfo):
                 $reviewInfo->verifyAndSave(); 
            endif;           
            Systemcount::run($celula->aula_id);
            event(new EntryAulaOnCelula($celula, $student));
        }
        else{
            //Se não for abertura de célula não pode ter aula_individual=1
            //force aula_individual=0 (padrão)
            $aula_individual=0;
        }
        
        $celula->students()->attach($student->id);
        $student->onMarcacaoAula($celula,$aula_individual);
        event(new AulaAgendada($celula, $student));
        return $celula->info();
    }

    public static function desmarcarStudent($student,$celula,$byAdm=0)
    {       
        $celula->students()->detach($student->id);
        $retorno=$student->onDesmarcacaoAula($celula,$byAdm);
        if($celula->students->count() ==0){
            //Se célula ficar vazia retirar aula_id e level 
            $celula->aula_id=null;
            $celula->aula_link=null;
            $celula->aula_level=null;
            $celula->aula_individual=0; //padrão
            ReviewInfo::deleteByCelula($celula->id); //limpando para nova abertura
            $celula->save();
            event(new ExitAulaOnCelula($celula, $student));
        }
        event(new AulaDesmarcada($celula,$student));
        return $retorno;

    }

    public static function getDadosToAgenda($celulas_id)
    {
        $resp = [];
        if ($celulas_id):
            $resp = Celula::join('teachers', 'celulas.teacher_id', '=', 'teachers.id')
                ->select('celulas.id', 'celulas.dia', 'celulas.horario', 'celulas.teacher_id',
                'teachers.nome as nome_professor')
                ->whereIn('celulas.id', $celulas_id)
                ->get();
        endif;
        return $resp;

    }

    public static function getCelulasAgendadas($student)
    {        
        $student_id=$student->id;
        $module_id=request('module_id');
        if(!$module_id):
            //pesquisa padrao == current module do student
            //pesquisa padrao modificada para == todos
            //$module_id=$student->module_id;
        endif;
        $disciplina_id=request('disciplina_id');
        $teacher_id=request('teacher_id');
        $start=request('start');
        $end=request('end');
       $query=Celula::join('celula_student','celulas.id','=','celula_student.celula_id')
       ->join('aulas','celulas.aula_id','=','aulas.id')
       ->join('disciplinas','aulas.disciplina_id','=','disciplinas.id')
       ->join('modules','aulas.module_id','=','modules.id')
       ->join('teachers','celulas.teacher_id','=','teachers.id')
       ->select('celulas.id', 'celulas.dia','celulas.horario','celulas.aula_id','celulas.teacher_id',
       'aulas.sigla as aula_sigla','aulas.module_id','aulas.disciplina_id',
       'disciplinas.nome as disciplina_nome','modules.nome as module_nome',
       'teachers.nome as teacher_nome')
       ->where('celula_student.student_id',$student_id);

       if($disciplina_id){
            $query->where('aulas.disciplina_id',$disciplina_id);
       }

       if( is_numeric($module_id) ){
        $query->where('aulas.module_id',$module_id);
       }
       
       if($teacher_id){
        $query->where('celulas.teacher_id',$teacher_id);
       }

       if($start){
        $query->where('celulas.dia','>=',$start);
       }
       if($end){
        $query->where('celulas.dia','<=',$end);
       }
       $result=$query->orderBy('celulas.dia','desc')->orderBy('celulas.horario','desc')->get();
       return $result;
    }

    /**
     * Método que informa quantas horas para iniciar
     * @return float tanto de horas para iniciar célula de aula
     */
    
    public function HoursToStart()
    {
        $dateNow=Carbon::now();
        //$dateNow=Carbon::createFromDate('2022-08-21 10:00'); //uso de teste
        $dateStart=Carbon::createFromDate($this->dia.' '.$this->horario);
        return $dateNow->floatDiffInHours($dateStart, false);
       
    }

    public function isOnLimitHoursToStart()
    {
        $limitHoursToStart=ConfiguracoesHelper::desmarcacao_hours_before(); //trazer do config
        return $this->HoursToStart() >=$limitHoursToStart;
    }

    public function onSaveAulaLink()
    {
        TelegramHelper::notificarAlunosDaCelula($this);        
        Mail::send(new NotificacaoAluno($this));
        //Implementar outras notificações se Necessário: WhatsApp
    }

    /**
     * Método que retorna células que não foram preenchidas a presença nas informações extras de aluno na célula
     * @param string $start data inicial período
     * @param string $end data final período
     * @param int $teacher_id professor a pesquisar
     * @return \Illuminate\Support\Collection células com pendências de preenchimento em presença
     */
    public static function getPendenciasInfoStudentOnCelula($start, $end, $teacher_id=null)
    {
        $query=Celula::join('celula_student','celulas.id','=','celula_student.celula_id')
        ->join('students','celula_student.student_id','=','students.id')
        ->join('aulas','celulas.aula_id','=','aulas.id')     
        ->join('teachers','celulas.teacher_id','=','teachers.id')
        ->select('celulas.id', 'celulas.dia','celulas.horario','celulas.aula_id','celulas.teacher_id',
        'aulas.sigla as aula_sigla',
        'celula_student.student_id','celula_student.presenca',
        'teachers.nome as teacher_nome')
        ->whereNull('celula_student.presenca');
        if($teacher_id){
            $query->where('celulas.teacher_id',$teacher_id);
        }

        if($start){
            $query->where('celulas.dia','>=',$start);
        }
        if($end){
            $query->where('celulas.dia','<=',$end);
        }
        $result=$query->orderBy('celulas.dia','asc')->orderBy('celulas.horario','asc')->get();
        return $result->unique('id')->values();
        
    }

    public static function getPivotsByStudentAndAula($student_id, $aula_id)
    {
        $query = \DB::table('celula_student')
            ->join('celulas','celulas.id','=','celula_student.celula_id')
            ->select('celula_student.*', 'celulas.dia','celulas.horario','celulas.aula_id')
            ->where('celula_student.student_id',$student_id)
            ->where('celula_student.presenca',1)
            ->where('celulas.aula_id',$aula_id);
        $query->orderBy('celulas.dia','asc')->orderBy('celulas.horario','asc');
        return $query->get();
    }
}
