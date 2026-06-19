<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFornecedorCamisetaTamanhoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'fornecedor_camiseta_tipo_id' => ['required', 'exists:fornecedor_camiseta_tipos,id'],
            'tamanho' => ['required', 'string', 'max:50'],
            'medidas' => ['nullable', 'json'],
            'ordem' => ['nullable', 'integer', 'min:0'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }
}
