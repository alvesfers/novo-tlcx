<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormaPagamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'entidade_id' => ['required', 'exists:entidades,id'],
            'nome' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'in:dinheiro,cartao_credito,cartao_debito,pix,outra'],
            'taxa_credito' => ['nullable', 'numeric', 'min:0', 'max:99.99'],
            'taxa_debito' => ['nullable', 'numeric', 'min:0', 'max:99.99'],
            'taxa_pix' => ['nullable', 'numeric', 'min:0', 'max:99.99'],
            'ativa' => ['nullable', 'boolean'],
            'observacao' => ['nullable', 'string'],
        ];
    }
}
