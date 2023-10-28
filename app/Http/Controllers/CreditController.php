<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreditRequest;
use App\Models\Credit;


class CreditController extends Controller
{
    public function getCredits($student_id)
    {
        $credits = Credit::where('student_id', $student_id)
            ->orderBy('data_acao', 'desc')
            ->get();
        return response()->json(["data" => $credits]);
    }

    public function store(CreditRequest $request)
    {
        try {
            $credit = \DB::transaction(function () use ($request) {
                $credit = Credit::storeCredit($request);
                return $credit;
            });
            return response()->json($credit);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


    }

    public function getLastCreditByAuthStudent()
    {
        $student = auth()->user()->student;
        if(!$student):
            return response()->json(['error'=> 'Usuário autenticado não é Aluno'],400);
        endif;
        $credit= Credit::where('student_id', $student->id)
        ->where('operacao','+')
        ->orderBy('data_acao', 'desc')
        ->first();
        return response()->json($credit);

    }
}
