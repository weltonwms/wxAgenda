<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreditRequest;
use App\Models\Credit;


class CreditController extends Controller
{
    public function getCredits($student_id)
    {
        $credits=Credit::where('student_id',$student_id)
        ->orderBy('data_acao','desc')
        ->get();
        return response()->json(["data"=>$credits]);
    }

    public function store(CreditRequest $request)
    {
        $credit=Credit::storeCredit($request);
        return response()->json($credit);

    }
}
