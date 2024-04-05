<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|exists:colors,code',
            'size' => 'required|in:XS,S,M,L,XL',
            'qty' => 'required|integer|min:1|max:100',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'É obrigatório escolher uma cor.',
            'code.exists' => 'A cor escolhida não existe.',
            'size.required' => 'É obrigatório escolher um tamanho.',
            'size.in' => 'O tamanho escolhido não existe.',
            'qty.required' => 'É obrigatório escolher uma quantidade.',
            'qty.integer' => 'A quantidade deve ser um número inteiro.',
            'qty.min' => 'A quantidade mínima é 1.',
            'qty.max' => 'A quantidade máxima é 100.',
        ];
    }
}
