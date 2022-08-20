<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Celula;
use App\Models\Cancellation;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'email','telefone','module_id','cidade',
    'endereco','cidade','uf'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function celulas()
    {
        return $this->belongsToMany(Celula::class);
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

    public function getModuleNome()
    {
        if($this->module){
            return $this->module->nome;
        }
    }

    public function onMarcacaoAula(Celula $celula)
    {
        $this->saldo_atual--;
        $this->save();
    }

    public function onDesmarcacaoAula(Celula $celula)
    {
        $this->saldo_atual++;
        $this->save();
        $cancellation= new Cancellation();
        $cancellation->student_id=$this->id;
        $cancellation->data_acao=date('Y-m-d H:i:s');
        $cancellation->horario=$celula->horario;
        $cancellation->dia=$celula->dia;
        $cancellation->teacher_id=$celula->teacher_id;
        $cancellation->aula_id=$celula->aula_id;
        $cancellation->save();
    }

    public function save(array $options = array())
    {
        if($this->saldo_atual < 0):
            throw new \Exception('Saldo Atual inválido.');
        endif;

        return parent::save($options);
    }
}
