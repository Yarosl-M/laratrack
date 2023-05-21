<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
    public function rules(): array {
        return [
            'current_password' => ['bail', 'required'],
            'password' => ['bail', 'required', 'min:6', 'max:40', 'regex:/[A-Za-z0-9*.!@#$%^&(){}[\]:;<>,.?\/~_+\-=|\\\]*/i',
                'confirmed'],
        ];
    }
    public function messages(): array {
        return [
            'current_password.required' => 'Это обязательное поле',
            'password.required' => 'Это обязательное поле',
            'password.min' => 'Пароль не должен быть короче 6 символов',
            'password.max' => 'Пароль не должен быть длиннее 40 символов',
            'password.regex' => 'Пароль содержит недопустимые символы',
            'password.confirmed' => 'Пароль неправильно подтверждён',
        ];
    }
}
