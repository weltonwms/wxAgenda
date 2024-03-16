<?php
namespace App\Helpers;

use Carbon\Carbon;
use App\Helpers\ConfiguracoesHelper;
use App\Models\Celula;


class FilterAgendaHelper
{
    private $start; 
    private $end;
    private $celulasBase;

    /**
     * Para Usar os Filtros é necessário delimitar um período Base para 
     * não estourar consulta enorme no banco de dados
     * @param string $startBase Data Início do período Base
     * @param string $endBase Data Fim do período Base
     */
    public function __construct($startBase, $endBase)
    {
        $this->setStart($startBase);
        $this->setEnd($endBase);
        $this->setCelulasBase();
    }

    /**
     * Celulas do Banco de Dados delimitadas por um período : start e end para
     * não ficar muito pesada a consulta. O Resultado contém as células com informações
     * de horário, turno, nome Professor, aula marcada se houver com informações da disciplina e módulo desta
     * As celulas bases além de limitar por período, excluem as celulas fechadas também.
     * Celulas fechadas são celulas estouradas do limite de aluno ou celula individual.
     */
    private function setCelulasBase()
    {
        //caso seja necessário pesquisas mais abrangentes incluindo celulas fechadas
        //implementar parametros opcionais para deixar a query mais abrangente
        $celula_limit = ConfiguracoesHelper::celula_limit();
        // considerando que células maior que limite (4) estão fechadas
        $queryBase = Celula::has('students', '<', $celula_limit)
            ->join('horarios', 'horarios.horario', 'celulas.horario')
            ->join('teachers', 'teachers.id', 'celulas.teacher_id')
            ->leftJoin('aulas', 'aulas.id', 'celulas.aula_id')
            ->select('celulas.*', 'horarios.turno_id', 'teachers.nome as teacher_nome',
            'aulas.disciplina_id', 'aulas.module_id','aulas.sigla as aula_sigla');
        
        $queryBase->where('celulas.dia', '>=', $this->start);
        $queryBase->where('celulas.dia', '<=', $this->end);
        //considerando que celula individual é uma celula fechada
        $queryBase->where('celulas.aula_individual','!=',1);

        $this->celulasBase = $queryBase->with('students')
            ->orderBy('celulas.dia')
            ->orderBy('celulas.horario')
            ->get();
    }

     /**
     * Filtra a partir das células bases por uma uma disciplina em algum turno.
     * Os parâmetro start e end são opcionais assumindo por padrão o valor de start e end da classe.
     * O objetivo disso é poder colocar uma janela maior para as celulas base e poder ficar fazendo vários filtros
     * depois em janelas de período menor.
    */
    public function filtroDisciplina($disciplina_id, $turno_id, $module_id = null, $start= null, $end=null)
    {
        $start= $start?$start:$this->start;
        $end= $end?$end:$this->end;
        $filtered = $this->celulasBase
            ->filter(function ($celula) use ($disciplina_id, $start, $end, $turno_id, $module_id) {
                $result = $celula->disciplina_id == $disciplina_id &&
                    $celula->turno_id == $turno_id &&
                    $celula->dia >= $start &&
                    $celula->dia <= $end;
                if ($module_id):
                    return $result && $celula->module_id == $module_id;
                endif;
                return $result;
            });
        return $filtered->values();
    }

    /**
     * Filtra a partir das células bases por uma aula em algum turno.
     * Os parâmetro start e end são opcionais assumindo por padrão o valor de start e end da classe.
     * O objetivo disso é poder colocar uma janela maior para as celulas base e poder ficar fazendo vários filtros
     * depois em janelas de período menor.
    */
    public function filtroAula($aula_id, $turno_id, $start=null, $end=null)
    {        
        $start= $start?$start:$this->start;
        $end= $end?$end:$this->end;
        $filtered = $this->celulasBase
            ->filter(function ($celula) use ($aula_id, $start, $end, $turno_id) {

                return $celula->aula_id == $aula_id &&
                    $celula->turno_id == $turno_id &&
                    $celula->dia >= $start &&
                    $celula->dia <= $end;
            });
        return $filtered->values();
    }

    /**
     * Método apenas para garantir data em formato correto. 
     * O Carbon consegue criar datas a partir de diversos formatos strings
     */
    private function setStart($start)
    {
       $carbonStart = Carbon::createFromDate($start);
       $this->start = $carbonStart->format('Y-m-d');
    }

   /**
     * Método apenas para garantir data em formato correto. 
     * O Carbon consegue criar datas a partir de diversos formatos strings
     */
    private function setEnd($end)
    {
        $carbonEnd = Carbon::createFromDate($end);
        $this->end = $carbonEnd->format('Y-m-d');
    }

    public function getCelulasBase()
    {
        return $this->celulasBase;
    }

    /**
     * Método de Apoio para formar um período inicial de pesquisa
     * conforme as configurações da aplicação.
     * Subtrai do parametro dia conforme configuração
     * @param string $dia data 
     */
    public static function getDayIntervalBefore($dia)
    {
        $interval=ConfiguracoesHelper::day_interval_before();
        $startCarbon = Carbon::createFromDate($dia)->subDays($interval);        
        return $startCarbon->format('Y-m-d');
    }

     /**
     * Método de Apoio para formar um período final de pesquisa
     * conforme as configurações da aplicação.
     * adiciona do parametro dia conforme configuração
     * @param string $dia data 
     */
    public static function getDayIntervalAfter($dia)
    {
        $interval=ConfiguracoesHelper::day_interval_after();
        $endCarbon = Carbon::createFromDate($dia)->addDays($interval);       
        return $endCarbon->format('Y-m-d');       
    }


}