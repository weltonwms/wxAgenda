<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getHorarioAttribute($value)
    {
       $date=new \DateTime($value);
	    return $date->format("H:i");
    }
}
