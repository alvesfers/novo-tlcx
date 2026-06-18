<?php

namespace App\Http\Controllers;

use App\Models\Dirigente;
use App\Models\Habilidade;
use Illuminate\Http\Request;

class DirigenteHabilidadeController extends Controller
{
    public function store(Request $request, Dirigente $dirigente)
    {
        $this->authorize('update', $dirigente);

        $validated = $request->validate([
            'habilidade_id' => 'required|exists:habilidades,id',
            'nivel' => 'required|string|in:iniciante,basico,intermediario,experiente,profissional',
            'observacao' => 'nullable|string',
        ]);

        $dirigente->habilidades()->syncWithoutDetaching([
            $validated['habilidade_id'] => [
                'nivel' => $validated['nivel'],
                'observacao' => $validated['observacao'] ?? null,
            ],
        ]);

        return redirect()->back()->with('success', 'Habilidade adicionada com sucesso!');
    }

    public function update(Request $request, Dirigente $dirigente, Habilidade $habilidade)
    {
        $this->authorize('update', $dirigente);

        $validated = $request->validate([
            'nivel' => 'required|string|in:iniciante,basico,intermediario,experiente,profissional',
            'observacao' => 'nullable|string',
        ]);

        $dirigente->habilidades()->updateExistingPivot($habilidade->id, [
            'nivel' => $validated['nivel'],
            'observacao' => $validated['observacao'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Habilidade atualizada com sucesso!');
    }

    public function destroy(Dirigente $dirigente, Habilidade $habilidade)
    {
        $this->authorize('update', $dirigente);

        $dirigente->habilidades()->detach($habilidade->id);

        return redirect()->back()->with('success', 'Habilidade removida com sucesso!');
    }
}
