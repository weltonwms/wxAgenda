<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Celula;
use App\Models\Cancellation;
use Carbon\Carbon;
use App\Helpers\ConfiguracoesHelper;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'email','telefone','module_id','cidade',
    'endereco','cidade','uf','horas_contratadas'];

    private $modules;

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
        return $this->belongsToMany(Celula::class)->withTimestamps();
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

    public function onMarcacaoAula(Celula $celula,$aula_individual=0)
    {
        $this->saldo_atual--;
        if($aula_individual==1):
            //para aulas individuais descontar do saldo 2 vezes.
            $this->saldo_atual--;
        endif;
        $this->save();
    }

    public function onDesmarcacaoAula(Celula $celula,$byAdm=0)
    {
        $credit_provided=0;
        //add saldo somente se desmarcação de adm ou estiver dentro dos limites
        if($byAdm || $this->isOnLimitCancellationsByMonth()){
            $this->saldo_atual++;
            $this->save();
            $credit_provided=1;
        }
        //fim add saldo (devolução de crédito)        
        $cancellation= new Cancellation();
        $cancellation->student_id=$this->id;
        $cancellation->data_acao=date('Y-m-d H:i:s');
        $cancellation->horario=$celula->horario;
        $cancellation->dia=$celula->dia;
        $cancellation->teacher_id=$celula->teacher_id;
        $cancellation->aula_id=$celula->aula_id;
        $cancellation->by_adm=$byAdm;
        $cancellation->credit_provided=$credit_provided;
        $cancellation->save();
        return ['success'=>true,'credit_provided'=>$credit_provided];
    }

    public function save(array $options = array())
    {
        if($this->saldo_atual < 0):
            throw new \Exception('Saldo Atual inválido.');
        endif;

        return parent::save($options);
    }

    public function countCancellationsByMonth($date=null,$byAdm=0)
    {
        if(!$date):
            $date= date('Y-m-d');
        endif;
        $carbonDate=Carbon::createFromDate($date);
        $query=\DB::table('cancellations')
        ->whereYear('data_acao', $carbonDate->format('Y'))
        ->whereMonth('data_acao', $carbonDate->format('m'))
        ->where('student_id',$this->id);
       if(is_numeric($byAdm)):
            $query->where('by_adm',$byAdm);
        endif;
        return $query->count();
      
    }

    public function isOnLimitCancellationsByMonth($date=null)
    {
        $limitCancellationsByMonth=ConfiguracoesHelper::desmarcacao_limit_by_month();
        return $this->countCancellationsByMonth($date) < $limitCancellationsByMonth;
    }

    /**
     * Retornas os módulos em que o aluno pode alternar.
     */
    public function getModules()
    {
        if($this->module){
            if(!$this->modules){
                $this->modules=\DB::table('modules')
                ->where('ordem','<=',$this->module->ordem)
                ->get();
            }
            return $this->modules;
        }
        return collect([]);
    }

    public function isModuleAllowed($module_id)
    {
        return $this->getModules()->contains('id',$module_id);
    }

    public function isModuleAnterior($module_id)
    {
        $moduleCorrent= $this->module;
        $moduleToCompare= $this->getModules()->firstWhere('id',$module_id);
        ///dd($moduleToCompare);

        if($moduleCorrent && $moduleToCompare):
            return $moduleToCompare->ordem < $moduleCorrent->ordem;
        endif;

        return false;
    }

    public function getAulasAgendadas($module_id=null,$disciplina_id=null)
    {
        $query=Aula::join('celulas', 'celulas.aula_id', '=', 'aulas.id')
            ->join('celula_student', 'celula_student.celula_id', '=', 'celulas.id')
            ->select('aulas.*', 'celulas.horario', 'celulas.dia', 'celulas.id as celula_id')
            ->where('celula_student.student_id', $this->id);

        if($module_id):
            $query->where('aulas.module_id', $module_id);
        endif;
        if($disciplina_id):
            $query->where('aulas.disciplina_id', $disciplina_id);
        endif;
        $result=$query->get();
        return $result;

    }
}
