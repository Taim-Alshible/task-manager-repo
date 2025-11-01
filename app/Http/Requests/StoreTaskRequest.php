<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required | string | max:40',
            'description' => 'nullable | string',
            'priority' => 'required | in:Low,Medium,High',
        ];
    }

    public function messages(): array
    {
        return [
            'title' => [
                'required' => 'you should enter a title',
                'string' => 'a title must be a string',
                'max' => 'your title is too long'
            ]
        ];
    }
}
