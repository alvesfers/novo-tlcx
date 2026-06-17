<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Traits\BulkDeleteable;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SecretariasController extends Controller
{
    use BulkDeleteable;
    public function index(): View
    {
        $secretarias = Entidade::where('tipo_entidade', 'secretaria')
            ->with('entidadePai')
            ->get();

        $nucleos = Entidade::where('tipo_entidade', 'nucleo')
            ->ativas()
            ->get();

        return view('secretarias.index', [
            'title' => 'Secretarias',
            'secretarias' => $secretarias,
            'nucleos' => $nucleos,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Entidade::class);

        $nucleos = Entidade::where('tipo_entidade', 'nucleo')
            ->ativas()
            ->get();

        return view('secretarias.create', compact('nucleos'));
    }

    public function show(Entidade $secretaria): View
    {
        $this->authorize('view', $secretaria);
        $secretaria->load('entidadePai', 'dirigenteVinculos.dirigente');

        return view('secretarias.show', compact('secretaria'));
    }

    public function edit(Entidade $secretaria): View
    {
        $this->authorize('update', $secretaria);

        $nucleos = Entidade::where('tipo_entidade', 'nucleo')
            ->ativas()
            ->get();

        return view('secretarias.edit', compact('secretaria', 'nucleos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Entidade::class);

        $validated = $request->validate([
            'entidade_pai_id' => 'required|exists:entidades,id',
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'tipo_secretaria' => 'required|in:aberta,fechada',
        ]);

        Entidade::create([
            ...$validated,
            'tipo_entidade' => 'secretaria',
        ]);

        return redirect()->route('secretarias.index')
            ->with('success', 'Secretaria criada com sucesso!');
    }

    public function update(Request $request, Entidade $secretaria): RedirectResponse
    {
        $this->authorize('update', $secretaria);

        $validated = $request->validate([
            'entidade_pai_id' => 'required|exists:entidades,id',
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'tipo_secretaria' => 'required|in:aberta,fechada',
            'ativo' => 'boolean',
        ]);

        $secretaria->update($validated);

        return redirect()->route('secretarias.index')
            ->with('success', 'Secretaria atualizada com sucesso!');
    }

    public function destroy(Entidade $secretaria): RedirectResponse
    {
        $this->authorize('delete', $secretaria);
        $secretaria->delete();

        return redirect()->route('secretarias.index')
            ->with('success', 'Secretaria deletada com sucesso!');
    }

    protected function getModel()
    {
        return Entidade::class;
    }
}
