<?php
namespace App\Helpers;

use App\Models\Systemcount;
use App\Models\Student;
use App\Models\Disciplina;
use Illuminate\Support\Facades\Log;

/**
 * Classe para apoiar o storeAgenda(GradeStoreRequest $request) da classe GradeController
 */
class EscolhaAutomaticaAulaHelper
{
      /**
     * Método que interfere no Contador da Escola se for Necessário atender o aluno 
     * 
     * @return int aula_id escolhida
     */
    public static function run(Student $student, $request)
    {
        //caso seja uma aula que o aluno já enviou a aula_id, será essa. 
        //(caso de célula já aberta ou alguma aula base em que o aluno escolhe aula).
        if ($request->aula_id):
            return $request->aula_id;
        endif;

        //No caso de não enviar a aula_id (caso de célula a abrir que não seja base).
        //O sistema escolherá a Aula, mas para isso precisára de ter no request módulo/disciplina
        if (!$request->disciplina_id):
            throw new \Exception('Seleção de Disciplina Obrigatória!', 422);
        endif;

        $disciplina = Disciplina::find($request->disciplina_id);
        if ($disciplina->base):
            throw new \Exception('Seleção de Aula Obrigatória!', 422);
        endif;

        if (!$request->module_id):
            throw new \Exception('Seleção de Módulo Obrigatória!', 422);
        endif;

        //Início da Escolha. 
        //Na escolha pelo sistema deve-se procurar uma aula baseada no contador da escola.
        $contadorEscola = Systemcount::getContador($request->module_id, $request->disciplina_id);
        $aulas = $student->getContagemAulasFeitasByModuleDisciplina($request->module_id, $request->disciplina_id);

        //exceções da interferência. Retornam aula_id correspondente ao contador da escola.
        $isOnCurrentModule = $student->module_id == $request->module_id;
        //ex1: Não está no módulo corrente
        if (!$isOnCurrentModule):
            return $aulas->firstWhere('ordem', $contadorEscola)->aula_id;
        endif;
        $hasAulaParaFazer = $aulas->contains(function ($aula) {
            return $aula->contador == 0;
        });
        //ex2: Não tem mais aulas para fazer
        if (!$hasAulaParaFazer):
            return $aulas->firstWhere('ordem', $contadorEscola)->aula_id;
        endif;
        $isContadorFeito = $aulas->contains(function ($aula) use ($contadorEscola) {
            return $aula->ordem == $contadorEscola && $aula->contador > 0;
        });
        //ex3: Aula do contador da escola não feita pelo aluno
        if (!$isContadorFeito):
            return $aulas->firstWhere('ordem', $contadorEscola)->aula_id;
        endif;
        //fim das exceções de interferencia
        //inicio da interferência no contador da escola.
        //Procura pela primeira aula a partir do contador da escola
        $firstAulaNaoFeita = $aulas->first(function ($aula) use ($contadorEscola) {
            return $aula->contador == 0 && $aula->ordem > $contadorEscola;
        });
        //Se não achar a partir do contador da escola, então assuma a primeira que encontrar desde o inicio.
        if (!$firstAulaNaoFeita) {
            $firstAulaNaoFeita = $aulas->firstWhere('contador', 0);
        }
        //interferencia com $firstAulaNaoFeita->ordem
        Systemcount::storeContador($request->module_id, $request->disciplina_id,$firstAulaNaoFeita->ordem);
        //log da interferencia
        $textoLog= "Contador Alterado: module_id: {$request->module_id} |
         diciplina_id: {$request->disciplina_id} | De: $contadorEscola | 
         Para: {$firstAulaNaoFeita->ordem} | By student: {$student->id}";
        Log::channel('daily_contador_escola_logs')->info($textoLog);

        return $firstAulaNaoFeita->aula_id;       
    }


}