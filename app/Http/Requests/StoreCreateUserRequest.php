<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreateUserRequest extends FormRequest
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
            'email'=>'required|unique:users,email|string|max:255',
            'user_type'=>'required|in:A,E',
            'password'=>'required|string|max:255',
            'blocked'=>'required|integer|min:0|max:1',
            'photo_url'=>'nullable|string|max:255',
        ];
    }
}
