<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTarefaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'evento_id' => 'nullable|exists:eventos,id',
            'tarefa_categoria_id' => 'nullable|exists:tarefa_categorias,id',
            'responsavel_user_id' => 'nullable|exists:users,id',
            'responsavel_dirigente_id' => 'nullable|exists:dirigentes,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:2000',
            'status' => 'required|string|in:pendente,em_andamento,concluida,cancelada',
            'prioridade' => 'required|string|in:baixa,media,alta,urgente',
            'data_inicio' => 'nullable|date',
            'data_limite' => 'nullable|date|after_or_equal:data_inicio',
            'observacao' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título da tarefa é obrigatório.',
            'status.required' => 'O status é obrigatório.',
            'prioridade.required' => 'A prioridade é obrigatória.',
            'data_limite.after_or_equal' => 'A data limite deve ser igual ou posterior à data de início.',
        ];
    }
}
