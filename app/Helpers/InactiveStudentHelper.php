<?php
namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class InactiveStudentHelper
{
    public static function inativarAlunosPorFaltaRecarga()
    {
        $dataLimite = now()->subDays(70); //regra de não poder ficar  70 dias sem recarga

        $students = Student::query()
            ->select(
                'students.id',
                'students.nome',
                'students.email',
                DB::raw('MAX(credits.data_acao) as ultima_recarga')
            )
            ->leftJoin('credits', function ($join) {
                $join->on('credits.student_id', '=', 'students.id')
                    ->where('credits.operacao', '+');
            })
            ->where('students.active', true)
            ->groupBy(
                'students.id',
                'students.nome',
                'students.email'
            )
            ->get();

        $studentsParaInativar = $students->filter(function ($s) use ($dataLimite) {
            return is_null($s->ultima_recarga) //inativando os sem recarga
                || Carbon::parse($s->ultima_recarga)->lt($dataLimite); //lt (less than)= menor que; inativando abaixo do limite
        });

        $idsParaInativar = $studentsParaInativar->pluck('id');
        if ($idsParaInativar->isEmpty()) {
            return 0;
        }

        Log::channel('inactive_students')->info(
            'Inativação automática por falta de recarga',
            [
                'total' => $studentsParaInativar->count(),
                'data_limite' => $dataLimite->toDateString(),
            ]
        );

        foreach ($studentsParaInativar as $s) {
            Log::channel('inactive_students')->info(
                'Aluno inativado',
                [
                    'id' => $s->id,
                    'nome' => $s->nome,
                    //'email' => $s->email,
                    'ultima_recarga' => $s->ultima_recarga,
                ]
            );
        }

       


        $mensagem = sprintf(
            "\n[%s] Inativado automaticamente por falta de recarga (>70 dias).",
            now()->format('Y-m-d H:i')
        );

        $mensagemSql = DB::getPdo()->quote($mensagem);

        Student::whereIn('id', $idsParaInativar)
            ->where('active', true)
            ->update([
                'active' => false,
                'observacao' => DB::raw(
                    "CONCAT(COALESCE(observacao, ''), {$mensagemSql})"
                )
            ]);

        return $idsParaInativar->count();

    }
}