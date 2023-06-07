<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'email','telefone','disponibilidade','active'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUsernameAttribute($value)
    {
        if($this->user){
            return $this->user->username;
        }
       
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

    /**
     * Retorna Lista de professores Completa se for adm;
     * Se não for retorna apenas o Professor corresponte ao usuário logado
     */
    public static function getListForCelulas()
    {
        $list=Teacher::getList();
        if(auth()->user()->isAdm){
            return $list;
        }
        return $list->reject(function ($value, $key) {
            return $key != auth()->user()->getIdTeacher();
        });

    }

    public function getNomeActive()
    {
       return $this->active?'<span class="badge badge-success f90">Sim</span>':
       '<span class="badge badge-danger f90">Não</span>';
    }    

    public static function getAllByFilter()
    {
        if(request()->filter_ativo!=null){
            session(['teacher_filter_ativo' => request()->filter_ativo]);
        }        
        return Teacher::where('active',session('teacher_filter_ativo',1))->get();
        
    }

}
