<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    protected $primaryKey = 'horario';
    public $incrementing = false;

    public function getHorarioAttribute($value)
    {
       $date=new \DateTime($value);
	    return $date->format("H:i");
    }

    public function getNomeTurno()
    {
        $nomes=['1'=>'Matutino','2'=>'Vespertino','3'=>'Noturno'];
        if(isset($nomes[$this->turno_id])){
            return $nomes[$this->turno_id];
        }

    }

    public static function getTurnosList()
    {
       return ['1'=>'Matutino','2'=>'Vespertino','3'=>'Noturno'];
    }

    public function verifyAndDelete()
    {
        //realizar alguma validação antes caso seja necessário!!
      return $this->delete();    
    }

    public static function verifyAndDestroy(array $ids)
    {
        //realizar alguma validação antes caso seja necessário!!
       return self::destroy($ids);    
    }

    public static function getList()
    {
        return self::all()->mapWithKeys(function($item){
                  
            return [$item->horario => $item->horario];
        });
    }
}
