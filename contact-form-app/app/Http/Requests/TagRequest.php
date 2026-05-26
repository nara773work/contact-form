<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:50|unique:tags,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'タグ名は必須です',
            'name.max' => '50字以内に収めて下さい',
            'name.unique' => 'そのタグ名は既に使用されています',
        ];
    }
}
