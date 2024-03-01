<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverAuthRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:255|unique:users',
            'store_name' => 'required|string|max:255',
            'type' => 'required',
            'price' => 'required',
            'scedule' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|max:2048',
            'store_image' => 'required|image|max:2048',

        ];
    }
}
