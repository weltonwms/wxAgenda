<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;

class PaymentWooApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pedidoId' => 'required|integer|unique:payments,pedido_id',
            'email' => 'required|email',
            'status' => 'required',
            'billing.first_name' => 'required',
            'billing.last_name' => 'required',
            'billing.phone' => 'required',
            'products' => 'required|array|min:1',
            'products.*.creditos' => 'required|integer|min:1',
            'data_criacao' => 'nullable|date',
            'total' => 'nullable|numeric',


        ];
    }

    public function messages()
    {
        return [
            'pedidoId.required' => 'O ID do pedido é obrigatório.',
            'pedidoId.unique' => 'O ID do pedido já foi processado.',
            'data_criacao.date' => 'Data de criação inválida. ',
            'email.required' => 'O e-mail do usuário é obrigatório.',
            'billing.phone.required' => 'O telefone do usuário é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',            
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $first = $validator->errors()->first();
        Log::channel("payment")->error("Stage Request failedValidation", [$first]);
        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => $first
            ],
            200
        ));
    }

}
