<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarzinhoCombRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'barzinho_id' => ['required', 'exists:barzinhos,id'],
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'preco_venda' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }
}
