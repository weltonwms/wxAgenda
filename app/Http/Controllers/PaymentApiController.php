<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentApiRequest;
use App\Http\Requests\PaymentWooApiRequest;
use App\Models\Payment;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class PaymentApiController extends Controller
{
    public function storeEv(PaymentApiRequest $request)
    {       
        return $this->store($request, 'extractDataRequestToDataPayment');
    }

    public function storeWoo(PaymentWooApiRequest $request)
    {
        return $this->store($request, 'extractDataRequestWooToDataPayment');
    }

    private function store($request, $methodExtract)
    {
        try {
            $info = \DB::transaction(function () use ($request, $methodExtract) {
                $payment = call_user_func([Payment::class, $methodExtract], $request->all());
                Payment::savePaymentAndCredits($payment);
                return $payment;
            });
            Log::channel("payment")->info("Stage Store Success",[$info]);
            return response()->json(['success' => true, 'message' => "Pagamento Processado com Sucesso!",'info'=>$info]);
        } 
        catch (QueryException $e){
            Log::channel("payment")->error("Stage Store QueryException",[$e->getMessage()]);
            return response()->json([
                'success'=>false, 
                'message' => "Erro de SQL Banco de Dados"
                ],
             500);
        }
        catch (\Exception $e) {
            $statusCode = 500;
            if($e->getCode() >=400 && $e->getCode()<600){
                $statusCode = $e->getCode();
            }    
            Log::channel("payment")->error("Stage Store Exception",[$e->getMessage()]);
            return response()->json(['success'=>false, 'message' => $e->getMessage()],
             $statusCode);
        }

    }


}
