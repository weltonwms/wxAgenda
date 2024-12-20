<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Request Apenas para Student
 */
class PerfilStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //apenas student
        return auth()->check() && auth()->user()->isStudent;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $student = auth()->user()->student;
        $id = $student->id;
        return [
            "nome"=>"required",
            "telefone"=>"required",           
            "cpf"=>"nullable|digits:11|unique:students,cpf,$id",
        ];
    }
}
