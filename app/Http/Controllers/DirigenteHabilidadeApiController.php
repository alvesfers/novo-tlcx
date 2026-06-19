<?php

namespace App\Http\Controllers;

use App\Models\Dirigente;
use App\Models\Habilidade;
use Illuminate\Http\Request;

class DirigenteHabilidadeApiController extends Controller
{
    /**
     * List all habilidades for a dirigente
     */
    public function habilidades(Dirigente $dirigente)
    {
        $this->authorize('view', $dirigente);

        $habilidades = $dirigente->habilidades()
            ->get()
            ->map(function ($habilidade) {
                return [
                    'id' => $habilidade->id,
                    'nome' => $habilidade->nome,
                    'nivel' => $habilidade->pivot->nivel,
                    'nivel_label' => \App\Enums\NivelHabilidade::from($habilidade->pivot->nivel)->label(),
                    'observacao' => $habilidade->pivot->observacao,
                ];
            });

        return response()->json($habilidades);
    }

    /**
     * Add a new habilidade
     */
    public function addHabilidade(Request $request, Dirigente $dirigente)
    {
        $this->authorize('update', $dirigente);

        $validated = $request->validate([
            'habilidade_id' => 'required|exists:habilidades,id',
            'nivel' => 'required|string|in:iniciante,basico,intermediario,experiente,profissional',
            'observacao' => 'nullable|string',
        ]);

        try {
            $habilidade = Habilidade::findOrFail($validated['habilidade_id']);

            // Check if already exists
            if ($dirigente->habilidades()->where('habilidade_id', $validated['habilidade_id'])->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta habilidade já foi adicionada ao dirigente!',
                ], 422);
            }

            $dirigente->habilidades()->attach($validated['habilidade_id'], [
                'nivel' => $validated['nivel'],
                'observacao' => $validated['observacao'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Habilidade adicionada com sucesso!',
                'habilidade' => [
                    'id' => $habilidade->id,
                    'nome' => $habilidade->nome,
                    'nivel' => $validated['nivel'],
                    'nivel_label' => \App\Enums\NivelHabilidade::from($validated['nivel'])->label(),
                    'observacao' => $validated['observacao'] ?? null,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar habilidade: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove a habilidade
     */
    public function removeHabilidade(Dirigente $dirigente, Habilidade $habilidade)
    {
        $this->authorize('update', $dirigente);

        try {
            $dirigente->habilidades()->detach($habilidade->id);

            return response()->json([
                'success' => true,
                'message' => 'Habilidade removida com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover habilidade: ' . $e->getMessage(),
            ], 422);
        }
    }
}
