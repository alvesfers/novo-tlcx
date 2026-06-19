<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Models\Habilidade;
use Illuminate\Http\Request;

class SecretariaHabilidadeController extends Controller
{
    public function index(Entidade $secretaria)
    {
        $this->authorize('view', $secretaria);

        $habilidades = $secretaria->habilidades()
            ->select('id', 'nome', 'descricao', 'ativo')
            ->get()
            ->toArray();

        return response()->json([
            'habilidades' => $habilidades
        ]);
    }

    public function store(Request $request, Entidade $secretaria)
    {
        $this->authorize('update', $secretaria);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $validated['entidade_id'] = $secretaria->id;
        $validated['ativo'] = $validated['ativo'] ?? true;

        $habilidade = Habilidade::create($validated);

        return response()->json([
            'success' => true,
            'habilidade' => $habilidade->toArray()
        ]);
    }

    public function update(Request $request, Entidade $secretaria, Habilidade $habilidade)
    {
        $this->authorize('update', $secretaria);

        // Verify that the habilidade belongs to this secretaria
        if ($habilidade->entidade_id !== $secretaria->id) {
            return response()->json([
                'success' => false,
                'message' => 'Habilidade não pertence a esta secretaria'
            ], 403);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $habilidade->update($validated);

        return response()->json([
            'success' => true,
            'habilidade' => $habilidade->toArray()
        ]);
    }

    public function destroy(Entidade $secretaria, Habilidade $habilidade)
    {
        $this->authorize('update', $secretaria);

        // Verify that the habilidade belongs to this secretaria
        if ($habilidade->entidade_id !== $secretaria->id) {
            return response()->json([
                'success' => false,
                'message' => 'Habilidade não pertence a esta secretaria'
            ], 403);
        }

        $habilidade->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
