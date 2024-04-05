<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->id),
            ],
            'user_type'=>'required|in:C,A,E',
            'blocked'=>'required|integer|min:0|max:1',
            'file_photo'=>'sometimes|image|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' =>  'O nome é obrigatório',
            'name.unique' =>    'O nome tem que ser único',
            'email.required' => 'O email é obrigatório',
            'email.email' =>    'O formato do email é inválido',
            'email.unique' =>   'O email tem que ser único',
            'file_foto.image' => 'O ficheiro com a foto não é uma imagem',
            'file_foto.size' => 'O tamanho do ficheiro com a foto tem que ser inferior a 4 Mb',
        ];
    }
}
