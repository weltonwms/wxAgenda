<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Basedisciplina implements Rule
{
    private $entidade = null; //representa uma disciplina em caso de edição;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($entidade)
    {
        $this->entidade = $entidade;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //dd($this->entidade);
        if($value==0){
            $query=\DB::table('disciplinas')->where('base',1);
            if($this->entidade){
                $query->where('id','<>',$this->entidade->id);
            }
            $totalDisciplinasBase = $query->count();
            if($totalDisciplinasBase==0){
                return false;
            }
        }
        return true;
        
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Pelo menos uma disciplina deve ser definida como Base.';
    }
}
