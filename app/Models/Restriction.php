<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restriction extends Model
{
    use HasFactory;
    protected $fillable = ['module_id','disciplina_id','level_start','level_end'];

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
}
