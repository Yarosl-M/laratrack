<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // for now
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'bail|required|max:1000',
            'files.*' => 'bail|max:5120|mimes:jpg,jpeg,png,gif,bmp,doc,docx,txt,pdf,rtf',
        ];
    }
}