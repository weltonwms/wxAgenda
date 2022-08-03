<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aula;

class Systemcount extends Model
{
    use HasFactory;

    public static function run($aula_id)
    {
        $aula=Aula::find($aula_id);

       
       
        if($aula && !$aula->disciplina->base){
            $systemCount=Systemcount::where('module_id',$aula->module_id)
            ->where('disciplina_id',$aula->disciplina_id)
            ->first();

            $maximo=Aula::where('module_id',$aula->module_id)
            ->where('disciplina_id',$aula->disciplina_id)
            ->max('ordem');
            
            $contador=$systemCount?($systemCount->contador+1):2;
            if($contador >$maximo){
                $contador=1;
            }
            if(!$systemCount){
                $systemCount= new Systemcount();
                $systemCount->module_id=$aula->module_id;
                $systemCount->disciplina_id=$aula->disciplina_id;
            }
           $systemCount->contador=$contador;
           $systemCount->save(); 
        }

    }
}
