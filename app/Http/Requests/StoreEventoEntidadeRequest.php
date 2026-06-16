<?php

namespace App\Http\Requests;

use App\Enums\TipoParticipacaoEvento;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventoEntidadeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'evento_id' => 'required|exists:eventos,id',
            'entidade_id' => 'required|exists:entidades,id',
            'tipo_participacao' => 'required|in:' . implode(',', TipoParticipacaoEvento::values()),
        ];
    }

    public function messages(): array
    {
        return [
            'evento_id.required' => 'O evento é obrigatório',
            'entidade_id.required' => 'A entidade é obrigatória',
            'tipo_participacao.required' => 'O tipo de participação é obrigatório',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $eventoId = $this->input('evento_id');
            $entidadeId = $this->input('entidade_id');

            $existe = \App\Models\EventoEntidade::where('evento_id', $eventoId)
                ->where('entidade_id', $entidadeId)
                ->exists();

            if ($existe) {
                $validator->errors()->add('entidade_id', 'Esta entidade já participa do evento');
            }
        });
    }
}
