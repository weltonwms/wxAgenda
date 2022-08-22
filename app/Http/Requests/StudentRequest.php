<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
        $student=$this->route('student');
        $id=$student?$student->id:'null';
        $userId=$student?$student->user_id:'null';
        return [
            "nome"=>"required",
            "telefone"=>"required",
            "email"=>"required|email|unique:students,email,$id",
            'username'=>"required|unique:users,username,$userId"
        ];
    }
}
