<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFornecedorCamisetaTipoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'fornecedor_id' => ['required', 'exists:fornecedores_camisetas,id'],
            'tipo_camiseta' => ['required', 'string', 'max:255'],
            'ordem' => ['nullable', 'integer', 'min:0'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }
}
