<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParticipanteExternoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'documento' => 'nullable|string|max:20',
            'genero' => 'nullable|in:m,f,outro',
            'data_nascimento' => 'nullable|date_format:Y-m-d',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório',
            'email.email' => 'O email deve ser válido',
            'genero.in' => 'O gênero deve ser M, F ou Outro',
        ];
    }
}
