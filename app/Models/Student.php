<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'email','telefone','modulo_id','cidade',
    'endereco','cidade','uf'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public static function verifyAndDestroy(array $ids)
    {
        //realizar alguma validação antes caso seja necessário!!
       return self::destroy($ids);    
    }

    public static function getList()
    {
        return self::all()->mapWithKeys(function($item){
                  
            return [$item->id => $item->nome];
        });
    }

    public function getUsernameAttribute($value)
    {
        if($this->user){
            return $this->user->username;
        }
       
    }
}
