<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'gender'      => 'required|integer|in:1,2,3',
            'email'       => 'required|string|email|max:255',
            'tel'         => 'required|string|regex:/^[0-9]{10,11}$/',
            'address'     => 'required|string|max:255',
            'building'    => 'nullable|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'detail'      => 'required|string|max:120',
        
        ];
    }

        public function messages(): array
{
    return [
        'first_name.required' => '名前（名）は必須です',
        'last_name.required'  => '名前（姓）は必須です',
        'gender.required'     => '性別の選択は必須です', 
        'email.required'      => 'メールアドレスは必須です',
        'email.email'         => 'メールアドレスの形式が正しくありません',
        'address.required'    => '住所の入力は必須です',
        'tel.required'        => '電話番号の入力は必須です',
        'tel.regex'           => '電話番号は10〜11桁の数字で入力して下さい',
        'category_id'         =>'カテゴリーを選択して下さい',
        'detail.required'     => 'お問い合わせ内容は必須です',
        'detail.max'          => 'お問い合わせ内容は120文字以内です',
    ];
}
}
