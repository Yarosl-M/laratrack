<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool { return true; }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'bail|required|email|unique:users',
            'username' => ['bail', 'required', 'regex:/[A-Za-z][A-Za-z0-9_]*/i', 'min:6', 'max:40', 'unique:users'],
            'name' => 'bail|nullable|max:100',
            'password' => ['bail', 'required', 'min:6', 'max:40', /* apparently single quotes make this work??? */ 'regex:/[A-Za-z0-9*.!@#$%^&(){}[\]:;<>,.?\/~_+\-=|\\\]*/i',
                'confirmed'],
        ];
    }
}
