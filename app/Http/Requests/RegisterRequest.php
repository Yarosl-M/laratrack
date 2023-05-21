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
    public function rules(): array {
        return [
            'email' => 'bail|required|email|unique:users',
            'username' => ['bail', 'required', 'regex:/[A-Za-z][A-Za-z0-9_]*/i', 'min:6', 'max:40', 'unique:users'],
            'name' => 'bail|nullable|max:100',
            'password' => ['bail', 'required', 'min:6', 'max:40', /* apparently single quotes make this work??? */ 'regex:/[A-Za-z0-9*.!@#$%^&(){}[\]:;<>,.?\/~_+\-=|\\\]*/i',
                'confirmed'],
        ];
    }
    public function messages(): array {
        return [
            'email.required' => 'Это поле обязательно для заполнения',
            'email.email' => 'Это поле должно быть корректным адресом e-mail',
            'email.unique' => 'Такой адрес e-mail уже занят',
            'username.required' => 'Это поле обязательно для заполнения',
            'username.regex' => 'Поле должно включать только латинские буквы, цифры и знак подчёркивания',
            'username.min' => 'Логин должен быть не короче 6 символов',
            'username.max' => 'Логин должен быть не длиннее 40 символов',
            'username.unique' => 'Такой логин уже занят',
            'name.max' => 'Имя должно быть не длиннее 100 символов',
            'password.required' => 'Это поле обязательно для заполнения',
            'password.min' => 'Пароль не должен быть короче 6 символов',
            'password.max' => 'Пароль не должен быть длиннее 40 символов',
            'password.regex' => 'Пароль содержит недопустимые символы',
            'password.confirmed' => 'Пароль неправильно подтверждён',
        ];
    }
}
