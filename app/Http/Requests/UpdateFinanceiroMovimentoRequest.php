<?php

namespace App\Http\Requests;

use App\Enums\FormaPagamento;
use App\Enums\TipoMovimentoFinanceiro;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFinanceiroMovimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'financeiro_categoria_id' => 'required|exists:financeiro_categorias,id',
            'evento_id' => 'nullable|exists:eventos,id',
            'tipo' => 'required|in:' . implode(',', TipoMovimentoFinanceiro::values()),
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'data_movimento' => 'required|date|date_format:Y-m-d|before_or_equal:today',
            'forma_pagamento' => 'required|in:' . implode(',', FormaPagamento::values()),
            'comprovante_url' => 'nullable|string|url',
            'observacao' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'financeiro_categoria_id.required' => 'A categoria é obrigatória',
            'financeiro_categoria_id.exists' => 'A categoria selecionada não existe',
            'tipo.required' => 'O tipo é obrigatório',
            'tipo.in' => 'O tipo deve ser entrada ou saída',
            'descricao.required' => 'A descrição é obrigatória',
            'valor.required' => 'O valor é obrigatório',
            'valor.numeric' => 'O valor deve ser um número',
            'valor.min' => 'O valor deve ser maior que zero',
            'data_movimento.required' => 'A data é obrigatória',
            'data_movimento.date_format' => 'A data deve estar no formato YYYY-MM-DD',
            'data_movimento.before_or_equal' => 'A data não pode ser futura',
            'forma_pagamento.required' => 'A forma de pagamento é obrigatória',
            'forma_pagamento.in' => 'A forma de pagamento inválida',
            'comprovante_url.url' => 'A URL do comprovante deve ser válida',
            'observacao.max' => 'A observação não pode exceder 1000 caracteres',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $categoriaId = $this->input('financeiro_categoria_id');
            $tipo = $this->input('tipo');

            if ($categoriaId && $tipo) {
                $categoria = \App\Models\FinanceiroCategoria::find($categoriaId);
                if ($categoria && $categoria->tipo->value !== $tipo) {
                    $validator->errors()->add(
                        'tipo',
                        'O tipo do movimento não corresponde ao tipo da categoria'
                    );
                }
            }
        });
    }
}
