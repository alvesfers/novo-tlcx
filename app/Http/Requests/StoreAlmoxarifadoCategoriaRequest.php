<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlmoxarifadoCategoriaRequest extends FormRequest
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
            'ativo' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'entidade_id.required' => 'A entidade é obrigatória.',
            'entidade_id.exists' => 'A entidade selecionada não existe.',
            'nome.required' => 'O nome da categoria é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'descricao.max' => 'A descrição não pode ter mais de 1000 caracteres.',
        ];
    }
}
