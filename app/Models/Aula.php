<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Aula extends Model
{
    use HasFactory;
    protected $fillable = ['module_id','disciplina_id','sigla','ordem'];

    public function module(){
        return $this->belongsTo("App\Models\Module");
    }

    public function disciplina(){
        return $this->belongsTo("App\Models\Disciplina");
    }

    public static function verifyAndDestroy(array $ids)
    {
        //realizar alguma validação antes caso seja necessário!!
       return self::destroy($ids);    
    }

    public static function getAulasByFiltro()
    {
        $query= self::query();
        if(request('module_id')):
            $query->where('module_id',request('module_id'));
        endif;
        if(request('disciplina_id')):
            $query->where('disciplina_id',request('disciplina_id'));
        endif;
        return $query->with('module')->with('disciplina')->get();
    }
}
