<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Models\Habilidade;
use Illuminate\Http\Request;

class HabilidadeController extends Controller
{
    public function store(Request $request, Entidade $entidade)
    {
        $this->authorize('create', Habilidade::class);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $validated['entidade_id'] = $entidade->id;
        $validated['ativo'] = $validated['ativo'] ?? true;

        $habilidade = Habilidade::create($validated);

        return redirect()->back()->with('success', 'Habilidade criada com sucesso!');
    }

    public function update(Request $request, Habilidade $habilidade)
    {
        $this->authorize('update', $habilidade);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $habilidade->update($validated);

        return redirect()->back()->with('success', 'Habilidade atualizada com sucesso!');
    }

    public function destroy(Habilidade $habilidade)
    {
        $this->authorize('delete', $habilidade);

        $habilidade->delete();

        return redirect()->back()->with('success', 'Habilidade removida com sucesso!');
    }
}
