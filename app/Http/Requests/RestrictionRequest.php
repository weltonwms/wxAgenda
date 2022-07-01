<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestrictionRequest extends FormRequest
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
            'module_id' => 'required',
            'disciplina_id' => 'required',
            'level_start' => 'required|integer',
            'level_end' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'module_id.required' => 'O campo módulo é obrigatório',
            'disciplina_id.required' => 'O campo disciplina é obrigatório',
        ];
    }
}
