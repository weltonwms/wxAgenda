<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'base'];

    public function aulas()
    {
        return $this->hasMany("App\Models\Aula");
    }


     public function verifyAndDelete()
    {
        //realizar alguma validação antes caso seja necessário!!

        $nrAulas = $this->aulas->count();
        
        if ($nrAulas > 0):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => "Aula(s) Relacionada(s) à Disciplina"]);
            return false;
        else:
            return $this->delete();
        endif;

        
    }

    public static function verifyAndDestroy(array $ids)
    {
        //realizar alguma validação antes caso seja necessário!!
        $nrAulas = \App\Models\Aula::whereIn("disciplina_id", $ids)->count();
        $nrTotal = $nrAulas +0;
        $msg = [];
       
        if ($nrAulas > 0):
            $msg[] = "Aula(s) Relacionada(s) à Disciplina";
        endif;
        if ($nrTotal > 0):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => implode("<br>", $msg)]);
            return false;
        else:
            return self::destroy($ids);
        endif;
         
    }

    public static function getList()
    {
        return self::all()->mapWithKeys(function($item){
                  
            return [$item->id => $item->nome];
        });
    }

    public function getNomeBase()
    {
       return $this->base?'<span class="badge badge-success f100">Sim</span>':"Não";
    }

    /**
     * Método útil para deixar uma única disciplina como base
     */
    public static function updateBases($disciplina)
    {
        if(!$disciplina){
            return false;
        }
        if($disciplina->base){
            self::where('id','<>',$disciplina->id)->update(['base'=>0]);
        }
    }

    /**
     * Retorna apenas as disciplinas que possuem aulas em determinado módulo.
     * Ou seja retona as disciplinas que um módulo possui.
     */
    public static function getDisciplinasInModule($module_id)
    {
        $disciplinas= Disciplina::select('disciplinas.*')
        ->distinct()
        ->join('aulas','disciplinas.id','=','aulas.disciplina_id')
        ->where('aulas.module_id',$module_id)
        ->get();
        return $disciplinas;
    }
}
