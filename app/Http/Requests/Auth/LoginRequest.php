<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email é obrigatório',
            'email.email' => 'Informe um email válido',
            'email.exists' => 'Este email não existe no sistema',
            'password.required' => 'Senha é obrigatória',
            'password.min' => 'Senha deve ter pelo menos 6 caracteres',
        ];
    }
}
