<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventoCronogramaController extends Controller
{
    public function listar(Evento $evento)
    {
        $this->authorize('view', $evento);

        return response()->json([
            'cronograma' => $evento->getCronogramaOrdenado(),
        ]);
    }

    public function adicionar(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'dia' => 'required|integer|min:1',
            'horario' => 'required|date_format:H:i',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'local' => 'nullable|string|max:255',
            'responsavel' => 'nullable|string|max:255',
            'duracao_minutos' => 'nullable|integer|min:1',
        ]);

        $cronograma = $evento->cronograma ?? [];
        $cronograma[] = array_merge($validated, ['id' => (string) Str::uuid()]);

        $evento->update(['cronograma' => $cronograma]);

        return response()->json([
            'success' => true,
            'message' => 'Atividade adicionada!',
            'cronograma' => $evento->getCronogramaOrdenado(),
        ]);
    }

    public function editar(Request $request, Evento $evento, $cronogramaId)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'dia' => 'required|integer|min:1',
            'horario' => 'required|date_format:H:i',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'local' => 'nullable|string|max:255',
            'responsavel' => 'nullable|string|max:255',
            'duracao_minutos' => 'nullable|integer|min:1',
        ]);

        $cronograma = collect($evento->cronograma ?? [])
            ->map(function ($item) use ($cronogramaId, $validated) {
                return $item['id'] === $cronogramaId
                    ? array_merge($item, $validated)
                    : $item;
            })
            ->values()
            ->toArray();

        $evento->update(['cronograma' => $cronograma]);

        return response()->json([
            'success' => true,
            'message' => 'Atividade atualizada!',
            'cronograma' => $evento->getCronogramaOrdenado(),
        ]);
    }

    public function remover(Evento $evento, $cronogramaId)
    {
        $this->authorize('update', $evento);

        $cronograma = collect($evento->cronograma ?? [])
            ->reject(fn ($item) => $item['id'] === $cronogramaId)
            ->values()
            ->toArray();

        $evento->update(['cronograma' => $cronograma]);

        return response()->json([
            'success' => true,
            'message' => 'Atividade removida!',
            'cronograma' => $evento->getCronogramaOrdenado(),
        ]);
    }
}
