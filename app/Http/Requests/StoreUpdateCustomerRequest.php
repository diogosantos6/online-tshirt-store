<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateCustomerRequest extends FormRequest
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
            'id'=>'required|exists:users,id',
            'nif'=>'nullable|digits:9',
            'address'=>'nullable|string|max:60',
            'default_payment_type'=>'nullable|in:VISA,PAYPAL,MC',
            'default_payment_ref' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($this->input('default_payment_type') === 'VISA' || $this->input('default_payment_type') === 'MC') {
                        if (!preg_match('/^\d{16}$/', $value)) {
                            $fail('Se o pagamento é VISA OU MC a referência de pagamento corresponde ao número do
                            cartão de crédito e deverá ter 16 dígitos.');
                        }
                    } elseif ($this->input('default_payment_type') === 'PAYPAL') {
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $fail('Se o pagamento é PAYPAL a referência de pagamento deverá ser um email válido.');
                        }
                    }
                },
            ],
            'name'=>'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->id),
            ],
            'user_type'=>'in:C,A,E',
            'blocked'=>'integer|min:0|max:1',
            'file_photo'=>'sometimes|image|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'nif.integer' => 'O nif tem de ter só numeros',
            'nif.digits' => 'O nif tem de ter 9 numeros',
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
