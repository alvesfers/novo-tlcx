<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTarefaComentarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tarefa_id' => 'required|exists:tarefas,id',
            'comentario' => 'required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'tarefa_id.required' => 'A tarefa é obrigatória.',
            'comentario.required' => 'O comentário é obrigatório.',
            'comentario.max' => 'O comentário não pode ter mais de 2000 caracteres.',
        ];
    }
}
