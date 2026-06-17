<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlmoxarifadoMovimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entidade_id' => 'required|exists:entidades,id',
            'almoxarifado_item_id' => 'required|exists:almoxarifado_itens,id',
            'evento_id' => 'nullable|exists:eventos,id',
            'tipo_movimento' => 'required|string|in:entrada,saida,ajuste,transferencia',
            'quantidade' => 'required|numeric|min:0',
            'descricao' => 'nullable|string|max:1000',
            'data_movimento' => 'required|date',
            'observacao' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'entidade_id.required' => 'A entidade é obrigatória.',
            'almoxarifado_item_id.required' => 'O item é obrigatório.',
            'tipo_movimento.required' => 'O tipo de movimento é obrigatório.',
            'quantidade.required' => 'A quantidade é obrigatória.',
            'data_movimento.required' => 'A data do movimento é obrigatória.',
        ];
    }
}
