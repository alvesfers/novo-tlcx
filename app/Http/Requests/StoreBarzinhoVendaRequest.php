<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarzinhoVendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'barzinho_id' => ['required', 'exists:barzinhos,id'],
            'evento_participante_id' => ['required', 'exists:evento_participantes,id'],
            'forma_pagamento_id' => ['nullable', 'exists:formas_pagamento,id'],
            'tipo_pagamento' => ['nullable', 'in:dinheiro,credito,debito,pix'],
            'descricao' => ['nullable', 'string', 'max:255'],
            'subtotal' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'desconto' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'observacao' => ['nullable', 'string'],
        ];
    }
}
