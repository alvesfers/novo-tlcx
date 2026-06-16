<?php

namespace App\Http\Requests;

use App\Enums\TipoParticipanteEvento;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventoParticipanteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'evento_id' => 'required|exists:eventos,id',
            'tipo_participante' => 'required|in:' . implode(',', TipoParticipanteEvento::values()),
            'dirigente_id' => 'required_if:tipo_participante,dirigente|exists:dirigentes,id',
            'participante_externo_id' => 'required_if:tipo_participante,externo|exists:participante_externos,id',
            'observacao' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'evento_id.required' => 'O evento é obrigatório',
            'tipo_participante.required' => 'O tipo de participante é obrigatório',
            'dirigente_id.required_if' => 'O dirigente é obrigatório para este tipo de participante',
            'participante_externo_id.required_if' => 'O participante externo é obrigatório para este tipo',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tipo = $this->input('tipo_participante');
            $diigenteId = $this->input('dirigente_id');
            $externoId = $this->input('participante_externo_id');

            if ($tipo === TipoParticipanteEvento::Dirigente->value && $externoId) {
                $validator->errors()->add('participante_externo_id', 'Não deve ser preenchido para dirigentes');
            }

            if ($tipo === TipoParticipanteEvento::Externo->value && $diigenteId) {
                $validator->errors()->add('dirigente_id', 'Não deve ser preenchido para participantes externos');
            }
        });
    }
}
