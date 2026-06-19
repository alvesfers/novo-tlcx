<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarzinhoProdutoConsignadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'barzinho_produto_id' => ['required', 'exists:barzinho_produtos,id'],
            'almoxarifado_item_id' => ['required', 'exists:almoxarifado_itens,id'],
            'tipo_comissao' => ['required', 'in:percentual,fixo'],
            'comissao_valor' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'comissao_vai_para_entidade_id' => ['required', 'exists:entidades,id'],
            'preco_custo_original' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'data_consignacao' => ['required', 'date'],
            'ativa' => ['nullable', 'boolean'],
        ];
    }
}
