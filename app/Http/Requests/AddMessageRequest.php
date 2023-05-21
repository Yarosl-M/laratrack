<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMessageRequest extends FormRequest
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
            'content' => 'bail|required|max:1000',
            'files' => 'nullable|bail|array|max:5',
            // idk why would i need gifs and lots of other stuff but that works i guess
            'files.*' => 'bail|max:5120|mimes:jpg,jpeg,png,gif,bmp,doc,docx,txt,log,pdf,rtf',
        ];
    }
    public function messages(): array {
        return [
            'content.required' => 'Сообщение обязательно для заполнения',
            'content.max' => 'Длина сообщения — до 1000 символов',
            'files.max' => 'Разрешено прикрепить до 5 файлов',
            'files.*.max' => 'Размер файла до 5 Мбайт',
            'files.*.mimes' => 'Файл имеет недопустимое расширение',
        ];
    }
}
