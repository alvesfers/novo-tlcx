<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventoTipoCamisetaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'evento_id' => ['required', 'exists:eventos,id'],
            'fornecedor_id' => ['required', 'exists:fornecedores_camisetas,id'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }
}
