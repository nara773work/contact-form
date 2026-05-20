<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'keyword' => 'nullable|string|max:255',
            'gender' => 'nullable|integer|in:0,1,2,3',
            'category_id' => 'nullable|integer|exists:categories,id',
            'date' => 'nullable|date',
        ];

        if ($this->is('api/*')) {
            $rules['gender'] = 'nullable|integer|in:1,2,3';
            $rules['page'] = 'nullable|integer|min:1';
            $rules['per_page'] = 'nullable|integer|min:1|max:100';

        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'keyword.max' => '255字以内に収めて下さい',
        ];
    }
}
