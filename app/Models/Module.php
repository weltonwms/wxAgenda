<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
     protected $fillable = ['nome'];

     public function aulas()
     {
         return $this->hasMany("App\Models\Aula");
     }


     public function verifyAndDelete()
    {
        //realizar alguma validação antes caso seja necessário!!
        $nrAulas = $this->aulas->count();
        
        if ($nrAulas > 0):
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => "Aula(s) Relacionada(s) ao Módulo"]);
            return false;
        else:
            return $this->delete();
        endif;
         
    }

    public static function verifyAndDestroy(array $ids)
    {
        //realizar alguma validação antes caso seja necessário!!
        $nrAulas = \App\Models\Aula::whereIn("module_id", $ids)->count();
        $nrTotal = $nrAulas +0;
        $msg = [];
       
        if ($nrAulas > 0):
            $msg[] = "Aula(s) Relacionada(s) ao Módulo";
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
}
