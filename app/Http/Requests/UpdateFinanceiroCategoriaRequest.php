<?php

namespace App\Http\Requests;

use App\Enums\TipoMovimentoFinanceiro;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFinanceiroCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:' . implode(',', TipoMovimentoFinanceiro::values()),
            'ativo' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da categoria é obrigatório',
            'nome.max' => 'O nome não pode exceder 255 caracteres',
            'tipo.required' => 'O tipo é obrigatório (entrada ou saída)',
            'tipo.in' => 'O tipo deve ser entrada ou saída',
        ];
    }
}
