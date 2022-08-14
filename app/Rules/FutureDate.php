<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FutureDate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $dateNow= date('Y-m-d');
        $dateRequest= $value;
        $valid=$dateRequest>=$dateNow;
        return $valid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Data no Passado';
    }
}
