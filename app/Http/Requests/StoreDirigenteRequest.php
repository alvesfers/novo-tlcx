<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Entidade;

class StoreDirigenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'genero' => 'nullable|string|in:m,f,outro',
            'data_nascimento' => 'nullable|date',
            'foto_url' => 'nullable|url',
            'foto' => 'nullable|image|max:2048',
            'ativo' => 'boolean',
            'entidade_id' => 'required|exists:entidades,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'entidade_id.required' => 'A entidade principal é obrigatória.',
            'entidade_id.exists' => 'A entidade selecionada não existe.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $entidade = Entidade::find($this->entidade_id);

            if ($entidade && !$entidade->isNucleo()) {
                $validator->errors()->add('entidade_id', 'O vínculo principal deve ser com um Núcleo.');
            }
        });
    }
}
