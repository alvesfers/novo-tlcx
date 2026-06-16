<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EntidadeController extends Controller
{
    public function index(): View
    {
        // TODO: Implementar autorização via policy
        // Mostrar apenas entidades que o usuário tem acesso
        $entidades = Entidade::ativas()->get();

        return view('entidades.index', [
            'title' => 'Entidades',
            'entidades' => $entidades,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Entidade::class);
        return view('entidades.create', [
            'title' => 'Nova Entidade',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Entidade::class);

        // TODO: Validação via Form Request
        $validated = $request->validate([
            'entidade_pai_id' => 'nullable|exists:entidades,id',
            'tipo_entidade' => 'required|in:diocese,nucleo,secretaria',
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'tipo_secretaria' => 'nullable|in:aberta,fechada',
        ]);

        Entidade::create($validated);

        return redirect()->route('entidades.index')
            ->with('success', 'Entidade criada com sucesso!');
    }

    public function show(Entidade $entidade): View
    {
        $this->authorize('view', $entidade);
        return view('entidades.show', [
            'title' => $entidade->nome,
            'entidade' => $entidade,
        ]);
    }

    public function edit(Entidade $entidade): View
    {
        $this->authorize('update', $entidade);
        return view('entidades.edit', [
            'title' => 'Editar ' . $entidade->nome,
            'entidade' => $entidade,
        ]);
    }

    public function update(Request $request, Entidade $entidade): RedirectResponse
    {
        $this->authorize('update', $entidade);

        // TODO: Validação via Form Request
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'tipo_secretaria' => 'nullable|in:aberta,fechada',
            'ativo' => 'boolean',
        ]);

        $entidade->update($validated);

        return redirect()->route('entidades.show', $entidade)
            ->with('success', 'Entidade atualizada com sucesso!');
    }

    public function destroy(Entidade $entidade): RedirectResponse
    {
        $this->authorize('delete', $entidade);
        $entidade->delete();

        return redirect()->route('entidades.index')
            ->with('success', 'Entidade deletada com sucesso!');
    }
}
