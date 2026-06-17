<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlmoxarifadoItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'almoxarifado_categoria_id' => 'nullable|exists:almoxarifado_categorias,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'unidade_medida' => 'required|string|in:unidade,caixa,pacote,litro,metro,kg,outro',
            'quantidade_minima' => 'nullable|numeric|min:0',
            'localizacao' => 'nullable|string|max:255',
            'status' => 'required|string|in:ativo,inativo,esgotado',
            'observacao' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do item é obrigatório.',
            'unidade_medida.required' => 'A unidade de medida é obrigatória.',
            'status.required' => 'O status é obrigatório.',
        ];
    }
}
