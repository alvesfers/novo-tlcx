<?php

namespace App\Http\Requests;

use App\Enums\TipoVinculo;
use App\Enums\CargoDirigente;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Entidade;
use App\Models\Dirigente;

class StoreDirigenteEntidadeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dirigente_id' => 'required|exists:dirigentes,id',
            'entidade_id' => 'required|exists:entidades,id',
            'tipo_vinculo' => 'required|in:' . implode(',', TipoVinculo::values()),
            'cargo' => 'required|in:' . implode(',', CargoDirigente::values()),
            'papel' => 'nullable|string|max:255',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'ativo' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'dirigente_id.required' => 'O dirigente é obrigatório.',
            'entidade_id.required' => 'A entidade é obrigatória.',
            'tipo_vinculo.required' => 'O tipo de vínculo é obrigatório.',
            'cargo.required' => 'O cargo é obrigatório.',
            'data_fim.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $dirigente = Dirigente::find($this->dirigente_id);
            $entidade = Entidade::find($this->entidade_id);

            if (!$dirigente || !$entidade) {
                return;
            }

            // Validação: vínculo principal deve ser com núcleo
            if ($this->tipo_vinculo === 'principal' && !$entidade->isNucleo()) {
                $validator->errors()->add('entidade_id', 'O vínculo principal deve ser com um Núcleo.');
            }

            // Validação: coordenação deve ser com diocese
            if ($this->tipo_vinculo === 'coordenacao' && !$entidade->isDiocese()) {
                $validator->errors()->add('entidade_id', 'O vínculo de coordenação deve ser com uma Diocese.');
            }

            // Validação: não duplicar vínculo
            $existeVinculo = $dirigente->vinculos()
                ->where('entidade_id', $this->entidade_id)
                ->whereNull('deleted_at')
                ->exists();

            if ($existeVinculo) {
                $validator->errors()->add('entidade_id', 'Este dirigente já possui um vínculo com esta entidade.');
            }
        });
    }
}
