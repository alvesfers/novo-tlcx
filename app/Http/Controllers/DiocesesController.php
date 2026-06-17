<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DiocesesController extends Controller
{
    public function index(): View
    {
        $dioceses = Entidade::where('tipo_entidade', 'diocese')
            ->with('entidadesFilhas')
            ->get();

        return view('dioceses.index', [
            'title' => 'Dioceses',
            'dioceses' => $dioceses,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Entidade::class);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        Entidade::create([
            ...$validated,
            'tipo_entidade' => 'diocese',
        ]);

        return redirect()->route('dioceses.index')
            ->with('success', 'Diocese criada com sucesso!');
    }

    public function update(Request $request, Entidade $diocese): RedirectResponse
    {
        $this->authorize('update', $diocese);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'ativo' => 'boolean',
        ]);

        $diocese->update($validated);

        return redirect()->route('dioceses.index')
            ->with('success', 'Diocese atualizada com sucesso!');
    }

    public function destroy(Entidade $diocese): RedirectResponse
    {
        $this->authorize('delete', $diocese);
        $diocese->delete();

        return redirect()->route('dioceses.index')
            ->with('success', 'Diocese deletada com sucesso!');
    }
}
