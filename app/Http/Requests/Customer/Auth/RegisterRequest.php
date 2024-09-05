<?php

namespace App\Http\Requests\Customer\Auth;

use App\Rules\EmailOrPhone;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    protected $redirect;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'f_name'                      =>  ['required','string'],
            'l_name'                      =>  ['required','string'],
            'registeration_option'        =>  ['required','unique:customers,registeration_option',new EmailOrPhone],
            'password'                    =>  ['required','confirmed','min:8'],
            'country'                     =>  ['required','string'],

        ];
    }
}
