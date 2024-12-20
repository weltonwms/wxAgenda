<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;

class PaymentApiRequest extends FormRequest
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
            'invoice.id' => 'required|integer|unique:payments,pedido_id', 
            'invoice.user.email' => 'required|email', 
            'invoice.product_info.course_credits' => 'required|integer|min:1', 
            'invoice.date' =>'nullable|date',
            'invoice.price' =>'nullable|numeric',
            'invoice.product_info.product_id' => 'nullable|integer',

        ];
    }

    public function messages()
    {
        return [
            'invoice.id.required' => 'O ID da invoice é obrigatório.',
            'invoice.id.unique' => 'O ID da invoice já foi processado.',
            'invoice.date.date' => 'Data inválida de invoice.date.',
            'invoice.user.email.required' => 'O e-mail do usuário é obrigatório.',
            'invoice.user.email.email' => 'O e-mail deve ser um endereço válido.',
            'invoice.product_info.course_credits.required' => 'Campo course_credits dentro de product_info obrigatório.',
            'invoice.product_info.course_credits.integer' => 'Os créditos do curso devem ser um número inteiro.',
            'invoice.product_info.course_credits.min' => 'Os créditos do curso devem ser no mínimo 1.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $first= $validator->errors()->first();
        Log::channel("payment")->error("Stage Request failedValidation",[$first]);
        throw new HttpResponseException(response()->json(
            [
            'success'=> false,
            'message' => $first
        ], 422));
    }
}
