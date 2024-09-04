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
    'endereco','cidade','uf','horas_contratadas','active'];

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

    public static function getList($includeInactive=false)
    {
        $query=$includeInactive?self::all():self::where('active',1)->get();
        return $query->mapWithKeys(function($item){
                  
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
            $credit_provided++;
            if($celula->aula_individual):
                //dobrar add saldo para devolução de Aula Individual
                $this->saldo_atual++;
                $credit_provided++;
            endif;
            $this->save();
            
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

    public function getNomeActive()
    {
       return $this->active?'<span class="badge badge-success f90">Sim</span>':
       '<span class="badge badge-danger f90">Não</span>';
    }

    public static function getAllByFilter()
    {
        if(request()->filter_ativo!=null){
            session(['student_filter_ativo' => request()->filter_ativo]);
        }        
        return Student::where('active',session('student_filter_ativo',1))->with('module')->get();
        
    }

    public function hasAulaIdWithPresenca($aula_id)
    {
        return $this->celulas()
            ->where('aula_id', $aula_id)
            ->wherePivot('presenca', 1)
            ->exists();
    }

    /**
     * Retorna first Celula de uma aula feita pelo Aluno, 
     * desde que ele tenha presença nessa aula.
     * @param int $aula_id Identificador de uma aula
     */
    public function getCelulaAulaIdWithPresenca($aula_id)
    {
        return $this->celulas()
            ->where('aula_id', $aula_id)
            ->wherePivot('presenca', 1)
            ->orderBy('celulas.dia', 'desc')
            ->first();

    }

    /**
     * Retorna uma lista de aulas conforme filtros passados como parametro.
     * Lista útil para ter uma relação de todas as aulas com o contador de cada uma.
     * O contator refere-se ao número de vezes que o aluno fez determinada aula.
     * @param int $module_id filtro para módulo
     * @param int $disciplina_id filtro para disciplina
     * @return \Illuminate\Support\Collection Lista de objetos no formato {"aula_id": 173,"sigla": "GRM - 10","ordem": 1,"contador": 4}
     */
    public function getContagemAulasFeitasByModuleDisciplina($module_id=null,$disciplina_id=null)
    {
        $query = \DB::table('aulas')
        ->select('aulas.id as aula_id', 'aulas.sigla', 'aulas.ordem', \DB::raw('COUNT(celula_student.celula_id) as contador'))
        ->leftJoin('celulas', 'aulas.id', '=', 'celulas.aula_id')
        ->leftJoin('celula_student', function ($join) {
            $join->on('celula_student.celula_id', '=', 'celulas.id')
                ->where('celula_student.student_id', '=', $this->id)
                ->where('celula_student.presenca', '=', 1);
        });
        if($module_id){
            $query->where('aulas.module_id', $module_id);
        }
        if($disciplina_id){
            $query->where('aulas.disciplina_id', $disciplina_id);
        }
       
        $aulas= $query->groupBy('aulas.id', 'aulas.sigla', 'aulas.ordem')->orderBy('aulas.ordem')
        ->get();
        return $aulas;
    }

    public function getAndamento($module_id=null,$disciplina_id=null)
    {
        $ct = $this->getContagemAulasFeitasByModuleDisciplina($module_id,$disciplina_id);
        $obj = new \stdClass();
        $obj->module_id = $module_id;
        $obj->disciplina_id = $disciplina_id;
        $obj->countAulas = $ct->count();
        $obj->countFeitas = $ct->where('contador','>',0)->count();
        $obj->percentualComplete = 0;
        if($obj->countAulas){
            $obj->percentualComplete = ($obj->countFeitas / $obj->countAulas)*100;
        }       
        $obj->mapeamento = $ct->map(function($item){
            $obj = new \stdClass();
            $obj->aula_id = $item->aula_id;
            $obj->sigla = $item->sigla;
            $obj->value = $item->contador > 0? 1: 0;
            return $obj;
        });
        return $obj;

    }
}
