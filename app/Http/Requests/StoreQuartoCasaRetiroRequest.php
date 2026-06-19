<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuartoCasaRetiroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'numero' => ['required', 'string', 'max:50'],
            'tipo' => ['required', 'string', 'max:100'],
            'capacidade' => ['required', 'integer', 'min:1'],
            'valor_diaria' => ['nullable', 'numeric', 'min:0'],
            'disponivel' => ['nullable', 'boolean'],
            'descricao' => ['nullable', 'string'],
        ];
    }
}
