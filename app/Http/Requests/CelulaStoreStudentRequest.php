<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CelulaStoreStudentRequest extends FormRequest
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
            'student_id'=>'required',
            'aula_id'=>'required',
            'celula_id'=>'required'
        ];
    }

    public function messages()
    {
        return[
            'student_id.required'=>"Seleção do Aluno é obrigatória",
            'aula_id.required'=>"Seleção da Aula é obrigatória",
            'celula_id.required'=>"Identificador da Célula é obrigatório"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => $validator->errors()->first()], 422));
    }
}
