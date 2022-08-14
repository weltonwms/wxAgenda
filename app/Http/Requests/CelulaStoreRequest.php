<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CelulaStoreRequest extends FormRequest
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
            'dia'=>'required',
            'horario'=>'required',
            'teacher_id'=>'required'
        ];
    }

    public function messages()
    {
        return[
            'dia.required'=>"Seleção do Dia é obrigatória",
            'teacher_id.required'=>"Seleção do Professor é obrigatória"
        ];
    }

    public function validarDiaHorario()
    {
        
        $dateNow= date('Y-m-d H:i');
        $dateRequest= $this->dia.' '.$this->horario;
        $valid=$dateRequest>$dateNow;
        if(!$valid){
            throw new HttpResponseException(response()->json([
                'error'=>'Data no passado não é autorizado'],422));
        }
        
        
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => $validator->errors()->first()], 422));
    }
}
