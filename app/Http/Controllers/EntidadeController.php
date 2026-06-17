<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Traits\BulkDeleteable;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EntidadeController extends Controller
{
    use BulkDeleteable;
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

    public function create()
    {
        $this->authorize('create', Entidade::class);
        return redirect()->route('entidades.index');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Entidade::class);

        try {
            $validated = $request->validate([
                'entidade_pai_id' => 'nullable|exists:entidades,id',
                'tipo_entidade' => 'required|in:diocese,nucleo,secretaria',
                'nome' => 'required|string|max:255',
                'email' => 'nullable|email',
                'tipo_secretaria' => 'nullable|in:aberta,fechada',
            ]);

            $entidade = Entidade::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Entidade criada com sucesso!',
                    'entidade' => $entidade,
                ]);
            }

            return redirect()->route('entidades.index')
                ->with('success', 'Entidade criada com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Erro na validação dos dados',
                ], 422);
            }
            throw $e;
        }
    }

    public function show(Entidade $entidade): View
    {
        $this->authorize('view', $entidade);
        return view('entidades.show', [
            'title' => $entidade->nome,
            'entidade' => $entidade,
        ]);
    }

    public function edit(Entidade $entidade)
    {
        $this->authorize('update', $entidade);
        return redirect()->route('entidades.index');
    }

    public function update(Request $request, Entidade $entidade)
    {
        $this->authorize('update', $entidade);

        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'nullable|email',
                'tipo_secretaria' => 'nullable|in:aberta,fechada',
                'ativo' => 'boolean',
            ]);

            $entidade->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Entidade atualizada com sucesso!',
                    'entidade' => $entidade,
                ]);
            }

            return redirect()->route('entidades.show', $entidade)
                ->with('success', 'Entidade atualizada com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Erro na validação dos dados',
                ], 422);
            }
            throw $e;
        }
    }

    public function destroy(Entidade $entidade): RedirectResponse
    {
        $this->authorize('delete', $entidade);
        $entidade->delete();

        return redirect()->route('entidades.index')
            ->with('success', 'Entidade deletada com sucesso!');
    }

    protected function getModel()
    {
        return Entidade::class;
    }
}
