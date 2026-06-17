<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Traits\BulkDeleteable;

class DiocesesController extends Controller
{
    use BulkDeleteable;
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

    public function create(): View
    {
        $this->authorize('create', Entidade::class);

        return view('dioceses.create');
    }

    public function show(Entidade $diocese): View
    {
        $this->authorize('view', $diocese);
        $diocese->load('entidadesFilhas', 'dirigenteVinculos.dirigente');

        return view('dioceses.show', compact('diocese'));
    }

    public function edit(Entidade $diocese): View
    {
        $this->authorize('update', $diocese);

        return view('dioceses.edit', compact('diocese'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Entidade::class);

        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'nullable|email',
            ]);

            $diocese = Entidade::create([
                ...$validated,
                'tipo_entidade' => 'diocese',
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Diocese criada com sucesso!',
                    'diocese' => $diocese,
                ]);
            }

            return redirect()->route('dioceses.index')
                ->with('success', 'Diocese criada com sucesso!');
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

    public function update(Request $request, Entidade $diocese)
    {
        $this->authorize('update', $diocese);

        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'nullable|email',
                'ativo' => 'boolean',
            ]);

            $diocese->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Diocese atualizada com sucesso!',
                    'diocese' => $diocese,
                ]);
            }

            return redirect()->route('dioceses.index')
                ->with('success', 'Diocese atualizada com sucesso!');
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

    public function destroy(Entidade $diocese): RedirectResponse
    {
        $this->authorize('delete', $diocese);
        $diocese->delete();

        return redirect()->route('dioceses.index')
            ->with('success', 'Diocese deletada com sucesso!');
    }

    protected function getModel()
    {
        return Entidade::class;
    }
}
