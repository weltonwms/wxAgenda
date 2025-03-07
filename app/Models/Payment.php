<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Credit;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewStudent;

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
        $isNewStudent = false;
        $student = Student::where('email', $payment->user_email)->first();
        if (!$student):
            $isNewStudent = true;
           // throw new \Exception("Nenhum aluno com o email: {$payment->user_email} encontrado", 404);
          $student = self::cadastrarNewStudentFromAPI($payment);
          //para reverter criação: destruir: student, user, payment, credit
        endif;

        //salvar payment
        $payment->save();

        //salvar credits e atualizar saldo_atual de student
        $requestStoreCredit = new \stdClass();
        $requestStoreCredit->operacao = "+";
        $requestStoreCredit->qtd = $payment->course_credits;
        $requestStoreCredit->obs = "Pg Automático {{$payment->plataforma}} Nr: {$payment->pedido_id}";
        $requestStoreCredit->student_id = $student->id;
        $requestStoreCredit->payment_id = $payment->id;
        Credit::storeCredit($requestStoreCredit); 
        if($isNewStudent){
            $requestStoreCredit->qtd = 1;
            $requestStoreCredit->obs = "Bônus Novo Aluno Nr: {$payment->pedido_id}";
            Credit::storeCredit($requestStoreCredit); 
        }     
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

        $payment->user_phone = optional($invoice->user)->phone;
        $payment->user_document = optional($invoice->user)->document;
        $payment->plataforma = "Academy EV";
        return $payment;

    }

    public static function extractDataRequestWooToDataPayment(array $requestArray)
    {
       // Pega todos os dados da requisição como um stdClass
       $data = json_decode(json_encode($requestArray));
       $billing = $data->billing;
       $products = $data->products;
       $status = mb_strtolower($data->status);
    
       if($status != "processing") {            
            throw new \Exception("Status diferente de processing", 400);
       }

       $payment = new Payment();
       $payment->pedido_id = $data->pedidoId;
       $payment->user_email = $data->email;       
       $payment->user_name = "{$billing->first_name} {$billing->last_name}";
       $payment->user_phone = $billing->phone;
       $payment->user_document =  $billing->cpf ?? null;
       $payment->pedido_date = $data->data_criacao ?? null;
       $payment->price = $data->total ?? null;
       
       foreach($products as $product):
            $payment->product_id = $product->produto_id ?? null;
            $payment->course_credits += $product->creditos;
       endforeach;
       $payment->plataforma = "Woocommerce";

       return $payment;
    }

    private static function cadastrarNewStudentFromAPI(Payment $payment)
    {
        
        if(!$payment->user_name){
            throw new \Exception("Campo name de user Obrigatorio", 422);
        }
        if(!$payment->user_phone){
            throw new \Exception("Campo phone de user Obrigatorio", 422);
        }
       $module =  \App\Models\Module::orderBy('ordem', 'asc')->first();

        $student = new Student();
        $student->nome = $payment->user_name;
        $student->email = $payment->user_email;
        $student->telefone = $payment->user_phone;
        $student->cpf = $payment->user_document;
        $student->module_id = $module->id;
        $student->horas_contratadas = $payment->course_credits;

        $senhaTemporaria = \Illuminate\Support\Str::random(12);
        $user = new  \App\Models\User();
        $user->username = $student->email;
        $user->password = bcrypt($senhaTemporaria);
        $user->tipo = "student";
       
        $user->save();
        $student->user_id = $user->id;
        $student->save();

        //Enviar Email com Senha Temporária
        Mail::send(new NewStudent($student, $senhaTemporaria) );
       
        return $student;

    }
}
