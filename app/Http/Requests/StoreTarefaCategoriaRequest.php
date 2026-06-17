<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTarefaCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entidade_id' => 'required|exists:entidades,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'cor' => 'nullable|string|max:7',
            'ativo' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'entidade_id.required' => 'A entidade é obrigatória.',
            'nome.required' => 'O nome da categoria é obrigatório.',
        ];
    }
}
