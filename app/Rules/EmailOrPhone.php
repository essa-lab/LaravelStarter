<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailOrPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        if(!(filter_var($value,FILTER_VALIDATE_EMAIL) ||  preg_match('/^\+?\d+$/', $value))){
            $fail('This Registeration Option Is Invalid, Please Enter Valid One.');
        }
    }
}
