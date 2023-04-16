<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CelulaAulaLinkRequest extends FormRequest
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
           
            'aula_id'=>'required',
            'aula_link'=>'required|url'
        ];
    }

    public function messages()
    {
        return[
            //'celula.required'=>"Nenhuma Célula Selecionada",
            'aula_id.required'=>"Necessário ter Aula para incluir Link",
            'aula_link.required'=>"Preencha o Link da Aula"
        ];
    }

   

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => $validator->errors()->first()], 422));
    }
}
