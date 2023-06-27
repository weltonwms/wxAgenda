<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Credit extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $dates = ['data_acao'];

    public static function storeCredit($request)
    {
        $credit = new Credit();
        $credit->data_acao = date('Y-m-d H:i:s');
        $credit->operacao = $request->operacao;
        $credit->qtd = $request->qtd;
        $credit->obs = $request->obs;
        $credit->student_id = $request->student_id;

        $student = Student::find($request->student_id);
        if (!$student->saldo_atual) {
            $student->saldo_atual = 0; //fugir no nulo
        }
        if ($request->operacao === "+") {
            $novoSaldo = $student->saldo_atual + $request->qtd;
        }
        else {
            $novoSaldo = $student->saldo_atual - $request->qtd;
        }


        $credit->saldo_anterior = $student->saldo_atual;
        $credit->saldo_posterior = $novoSaldo;
        $credit->save();

        $student->saldo_atual = $novoSaldo;
        $student->save();
        return $credit;
    }
   

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d (H:i)');
    }



}
