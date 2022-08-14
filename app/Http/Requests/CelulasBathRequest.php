<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FutureDate;
use App\Rules\PeriodoFim;

class CelulasBathRequest extends FormRequest
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
            'periodo_inicio'=>['required', new FutureDate],
            'periodo_fim'=>['required', new FutureDate, new PeriodoFim($this->periodo_inicio)],
            'teacher_id'=>'required'
        ];
    }

    public function messages()
    {
        return[
            'periodo_inicio.required'=>'O Campo Início é obrigatório',
            'periodo_fim.required'=>'O Campo Fim é obrigatório',
            'teacher_id.required'=>'O Campos Professor é obrigatório',
        ];
    }
}
