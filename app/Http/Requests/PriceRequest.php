<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceRequest extends FormRequest
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
            'unit_price_catalog' => 'sometimes|numeric',
            'unit_price_own' => 'sometimes|numeric',
            'unit_price_catalog_discount' => 'sometimes|numeric',
            'unit_price_own_discount' => 'sometimes|numeric',
            'qty_discount' => 'sometimes|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'unit_price_catalog.numeric' => 'O campo Preço Catálogo deve ser um número',
            'unit_price_own.numeric' => 'O campo Preço Próprio deve ser um número',
            'unit_price_catalog_discount.numeric' => 'O campo Preço Catálogo com Desconto deve ser um número',
            'unit_price_own_discount.numeric' => 'O campo Preço Próprio com Desconto deve ser um número',
            'qty_discount.numeric' => 'O campo Desconto por Quantidade deve ser um número',
        ];
    }
}
