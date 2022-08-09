<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
       $date=new \DateTime($value);
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
        $info=new \stdClass();
        $info->id=$this->id;
        $info->horario=$this->horario;
        $info->dia=$this->getDiaFormatado();
        $info->teacher=$this->teacher->nome;
        
        return $info;
    }
}
