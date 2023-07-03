<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'username' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'phone' => 'regex:/^9665\d{8}$/|unique:users,phone',
            'password' => 'required|min:8|confirmed',
            'university' => 'required',
            'faculty' => 'required',
            'department' => 'required'
        ];
    }
}
