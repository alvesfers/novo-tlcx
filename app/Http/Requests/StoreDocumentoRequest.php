<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entidade_id' => 'required|exists:entidades,id',
            'evento_id' => 'nullable|exists:eventos,id',
            'documento_categoria_id' => 'nullable|exists:documento_categorias,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:2000',
            'arquivo' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,txt|max:10240',
            'tipo_documento' => 'required|string|in:geral,ata,financeiro,evento,formacao,liturgia,imagem,outro',
            'visibilidade' => 'required|string|in:publico,privado',
        ];
    }

    public function messages(): array
    {
        return [
            'entidade_id.required' => 'A entidade é obrigatória.',
            'titulo.required' => 'O título é obrigatório.',
            'arquivo.required' => 'O arquivo é obrigatório.',
            'arquivo.mimes' => 'O arquivo deve ser um dos seguintes tipos: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, TXT',
            'arquivo.max' => 'O arquivo não pode ter mais de 10MB.',
            'tipo_documento.required' => 'O tipo de documento é obrigatório.',
            'visibilidade.required' => 'A visibilidade é obrigatória.',
        ];
    }
}
