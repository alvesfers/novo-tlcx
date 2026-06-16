<?php

namespace App\Http\Requests;

use App\Enums\EscopoEvento;
use App\Enums\StatusEvento;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_evento_id' => 'required|exists:tipo_eventos,id',
            'entidade_criadora_id' => 'required|exists:entidades,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date_format:Y-m-d\TH:i',
            'data_fim' => 'nullable|date_format:Y-m-d\TH:i',
            'local' => 'nullable|string|max:255',
            'escopo' => 'required|in:' . implode(',', EscopoEvento::values()),
            'status' => 'required|in:' . implode(',', StatusEvento::values()),
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_evento_id.required' => 'O tipo de evento é obrigatório',
            'entidade_criadora_id.required' => 'A entidade criadora é obrigatória',
            'nome.required' => 'O nome do evento é obrigatório',
            'data_inicio.required' => 'A data de início é obrigatória',
            'escopo.required' => 'O escopo do evento é obrigatório',
            'status.required' => 'O status é obrigatório',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $dataFim = $this->input('data_fim');
            $dataInicio = $this->input('data_inicio');

            if ($dataFim && $dataInicio && $dataFim < $dataInicio) {
                $validator->errors()->add('data_fim', 'A data de término deve ser posterior à data de início');
            }
        });
    }
}
