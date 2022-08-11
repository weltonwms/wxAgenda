<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Systemcount;

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

    public static function storeStudent($student_id, $celula_id, $aula_id)
    {
        //validar se pode
        //ações em créditos
        //ações em systemCount

        $celula = Celula::find($celula_id);
        if (!$celula->aula_id) {
            //abrindo célula para aula 
            $celula->aula_id = $aula_id;
            $celula->save();
            //rodar o systemCount 
            Systemcount::run($celula->aula_id);
        }
        $celula->students()->attach($student_id);
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
