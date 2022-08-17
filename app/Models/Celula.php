<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Systemcount;
use App\Helpers\StoreStudentHelper;

class Celula extends Model
{
    use HasFactory;


    public function students()
    {
        return $this->belongsToMany(Student::class);

    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
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
       
        $celulas = Celula::where('dia', '>=', $start)->where('dia', '<=', $end)
            ->where('teacher_id', $teacher_id)
            ->withCount('students')
            ->with('aula')
            ->get();

        
        $mapCelulas = $celulas->map(function ($celula) {
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
            if ($celula->students_count > 3) {
                $obj->backgroundColor = '#dc3545';
                $obj->textColor='#fff';
            }
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

    public static function storeStudent($student, $celula_id, $aula_id)
    {
        //validar se pode
        //ações em créditos
        //ações em systemCount

        $celula = Celula::find($celula_id);
        $helper= new StoreStudentHelper();
        $helper->validarStore($student,$celula,$aula_id);
        
        if (!$celula->aula_id) {
            //abrindo célula para aula 
            $celula->aula_id = $aula_id;
            $celula->save();
            //rodar o systemCount 
            Systemcount::run($celula->aula_id);
        }
        $celula->students()->attach($student->id);
        return $celula->info();
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
}
