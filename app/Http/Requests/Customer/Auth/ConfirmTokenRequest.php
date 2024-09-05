<?php

namespace App\Http\Requests\Customer\Auth;

use App\Rules\EmailOrPhone;
use Illuminate\Foundation\Http\FormRequest;

class ConfirmTokenRequest extends FormRequest
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
            'token'                    =>  ['required','string'],
        ];
    }
}
