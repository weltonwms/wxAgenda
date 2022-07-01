<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Basedisciplina;

class DisciplinaRequest extends FormRequest
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
        $disciplina=$this->route('disciplina');
        $id=$disciplina?$disciplina->id:'null';
        
        return [
            "nome"=>"required|unique:disciplinas,nome,$id",
            "base"=>[new Basedisciplina($disciplina)],
        ];
    }
}
