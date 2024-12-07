<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Credit;

class Payment extends Model
{
    protected $casts = [
        'pedido_date' => 'date', // Ou 'datetime' se incluir hora
    ];
    /**
     * Salvar o Payment e salvar Credits Atualizando saldo atual student
     * @param \App\Models\Payment $payment já deve estar preenchido com os dados de pagamento
     * @return void
     */
    public static function savePaymentAndCredits(Payment $payment)
    {        
        //procurar student
        $student = Student::where('email', $payment->user_email)->first();
        if (!$student):
            throw new \Exception("Nenhum aluno com o email: {$payment->user_email} encontrado", 404);
        endif;

        //salvar payment
        $payment->save();

        //salvar credits e atualizar saldo_atual de student
        $requestStoreCredit = new \stdClass();
        $requestStoreCredit->operacao = "+";
        $requestStoreCredit->qtd = $payment->course_credits;
        $requestStoreCredit->obs = "Pg Automático Academy";
        $requestStoreCredit->student_id = $student->id;
        $requestStoreCredit->payment_id = $payment->id;
        Credit::storeCredit($requestStoreCredit);
    }

    /**
     * Tratar o formato que vem da API para o Formato da class Payment
     * @param array $request
     * @return Payment
     */
    public static function extractDataRequestToDataPayment(array $requestArray)
    {
        // Pega todos os dados da requisição como um stdClass
        $data = json_decode(json_encode($requestArray));
        $invoice = $data->invoice;

        $payment = new Payment();
        $payment->pedido_id = $invoice->id;
        $payment->user_email = $invoice->user->email;
        $payment->course_credits = $invoice->product_info->course_credits;
        $payment->user_name = optional($invoice->user)->name;
        $payment->pedido_date = $invoice->date ?? null;
        $payment->product_id = optional($invoice->product_info)->product_id;
        $payment->price = $invoice->price ?? null;

        return $payment;

    }
}
