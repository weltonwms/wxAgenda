<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PeriodoFim implements Rule
{
    private $periodoInicio;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($periodoInicio)
    {
       $this->periodoInicio=$periodoInicio;
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
        return $value>=$this->periodoInicio;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Fim deve ser maior que Início';
    }
}
