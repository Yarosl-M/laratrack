<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return $this->user()->can('create', Ticket::class);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'bail|required|max:100',
            'content' => 'bail|required|max:1000',
            'files.*' => 'bail|max:5120|mimes:jpg,jpeg,png,gif,bmp,doc,docx,txt,pdf,rtf',
        ];
    }
}
