<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TshirtImageRequest extends FormRequest
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
            'costumer_id' => 'nullable|int|exists:costumers,id',
            'category_id' => 'nullable|int|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'file_image' => [
                Rule::requiredIf(function () {
                    return $this->isMethod('post');
                }),
                'image',
                'max:4096',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'costumer_id.exists' => 'O campo costumer_id deve ser um valor existente na tabela costumers.',
            'category_id.exists' => 'Deve selecionar uma categoria existente da tabela categories.',
            'category_id.int' => 'O campo category_id deve ser um número inteiro.',
            'name.required' => 'É obrigatório indicar o nome da imagem',
            'name.string' => 'O nome da imagem deve ser uma string',
            'name.max' => 'O nome da imagem não deve ter mais de 255 caracteres',
            'description.required' => 'É obrigatório indicar a descrição da imagem',
            'description.string' => 'A descrição da imagem deve ser uma string',
            'description.max' => 'A descrição da imagem não deve ter mais de 255 caracteres',
            'file_image.required' => 'É obrigatório indicar o ficheiro da imagem',
            'file_image.image' => 'O ficheiro deve ser uma imagem',
            'file_image.max' => 'O ficheiro não deve ter mais de 4MB',
        ];
    }
}
