<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CelulaInfoStudentRequest extends FormRequest
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
        $requiredNotas = $this->presenca? 'required':'nullable';
       return [
            'presenca' => 'required',
            'n1'=>"$requiredNotas|numeric|between:0,5",
            'n2'=>"$requiredNotas|numeric|between:0,5",
            'n3'=>"$requiredNotas|numeric|between:0,5",
            'n4'=>"$requiredNotas|numeric|between:0,5",
            
        ];
    }

    public function messages()
    {
        return[
            "n1.required"=>"Notas Obrigatórias quando há presença",
            "n2.required"=>"Notas Obrigatórias quando há presença",
            "n3.required"=>"Notas Obrigatórias quando há presença",
            "n4.required"=>"Notas Obrigatórias quando há presença",
        ];
    }

    public function attributes()
    {
        return [
            'presenca' => "presença",
            "n1"=>'Nota Interaction',
            "n2"=>'Nota Speaking',
            "n3"=>'Nota Listening',
            "n4"=>'Nota Comprehension',
        ];
    }


   

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => $validator->errors()->first()], 422));
    }
}
