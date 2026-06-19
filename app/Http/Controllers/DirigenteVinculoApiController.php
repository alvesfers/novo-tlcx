<?php

namespace App\Http\Controllers;

use App\Models\Dirigente;
use App\Models\DirigenteEntidade;
use App\Models\Entidade;
use Illuminate\Http\Request;

class DirigenteVinculoApiController extends Controller
{
    /**
     * List all vinculos for a dirigente
     */
    public function vinculos(Dirigente $dirigente)
    {
        $this->authorize('view', $dirigente);

        $vinculos = $dirigente->vinculos()
            ->with('entidade')
            ->get()
            ->map(function ($vinculo) {
                return [
                    'id' => $vinculo->id,
                    'entidade_id' => $vinculo->entidade_id,
                    'entidade_nome' => $vinculo->entidade->nome,
                    'tipo_entidade' => $vinculo->entidade->tipo_entidade,
                    'cargo' => $vinculo->cargo?->value,
                    'cargo_label' => $vinculo->cargo?->label(),
                    'tipo_vinculo' => $vinculo->tipo_vinculo?->value,
                    'data_inicio' => $vinculo->data_inicio?->format('Y-m-d'),
                    'ativo' => $vinculo->ativo,
                ];
            });

        return response()->json($vinculos->values());
    }

    /**
     * Add a new vinculo
     */
    public function addVinculo(Request $request, Dirigente $dirigente)
    {
        $this->authorize('update', $dirigente);

        $validated = $request->validate([
            'entidade_id' => 'required|exists:entidades,id',
            'cargo' => 'required|string',
        ]);

        try {
            // Check if entidade exists
            $entidade = Entidade::findOrFail($validated['entidade_id']);

            // Create vinculo
            $vinculo = $dirigente->vinculos()->create([
                'entidade_id' => $validated['entidade_id'],
                'cargo' => $validated['cargo'],
                'tipo_vinculo' => 'principal',
                'data_inicio' => now(),
                'ativo' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vínculo adicionado com sucesso!',
                'vinculo' => [
                    'id' => $vinculo->id,
                    'entidade_id' => $vinculo->entidade_id,
                    'entidade_nome' => $entidade->nome,
                    'tipo_entidade' => $entidade->tipo_entidade,
                    'cargo' => $vinculo->cargo?->value,
                    'cargo_label' => $vinculo->cargo?->label(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar vínculo: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update a vinculo's cargo
     */
    public function updateVinculo(Request $request, Dirigente $dirigente, DirigenteEntidade $vinculo)
    {
        if ($vinculo->dirigente_id !== $dirigente->id) {
            abort(404);
        }

        $this->authorize('update', $dirigente);

        $validated = $request->validate([
            'cargo' => 'required|string',
        ]);

        try {
            $vinculo->update(['cargo' => $validated['cargo']]);

            return response()->json([
                'success' => true,
                'message' => 'Vínculo atualizado com sucesso!',
                'vinculo' => [
                    'id' => $vinculo->id,
                    'entidade_id' => $vinculo->entidade_id,
                    'entidade_nome' => $vinculo->entidade->nome,
                    'cargo' => $vinculo->cargo?->value,
                    'cargo_label' => $vinculo->cargo?->label(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar vínculo: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove a vinculo
     */
    public function removeVinculo(Dirigente $dirigente, DirigenteEntidade $vinculo)
    {
        if ($vinculo->dirigente_id !== $dirigente->id) {
            abort(404);
        }

        $this->authorize('update', $dirigente);

        try {
            $vinculo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vínculo removido com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover vínculo: ' . $e->getMessage(),
            ], 422);
        }
    }
}
