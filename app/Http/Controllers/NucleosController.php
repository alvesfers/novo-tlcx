<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Traits\BulkDeleteable;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NucleosController extends Controller
{
    use BulkDeleteable;
    public function index(): View
    {
        $nucleos = Entidade::where('tipo_entidade', 'nucleo')
            ->with('entidadePai', 'entidadesFilhas')
            ->get();

        $dioceses = Entidade::where('tipo_entidade', 'diocese')
            ->ativas()
            ->get();

        return view('nucleos.index', [
            'title' => 'Núcleos',
            'nucleos' => $nucleos,
            'dioceses' => $dioceses,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Entidade::class);

        $dioceses = Entidade::where('tipo_entidade', 'diocese')
            ->ativas()
            ->get();

        return view('nucleos.create', compact('dioceses'));
    }

    public function show(Entidade $nucleo): View
    {
        $this->authorize('view', $nucleo);
        $nucleo->load('entidadePai', 'entidadesFilhas', 'dirigenteVinculos.dirigente');

        return view('nucleos.show', compact('nucleo'));
    }

    public function edit(Entidade $nucleo): View
    {
        $this->authorize('update', $nucleo);

        $dioceses = Entidade::where('tipo_entidade', 'diocese')
            ->ativas()
            ->get();

        return view('nucleos.edit', compact('nucleo', 'dioceses'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Entidade::class);

        try {
            $validated = $request->validate([
                'entidade_pai_id' => 'required|exists:entidades,id',
                'nome' => 'required|string|max:255',
                'email' => 'nullable|email',
            ]);

            $nucleo = Entidade::create([
                ...$validated,
                'tipo_entidade' => 'nucleo',
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Núcleo criado com sucesso!',
                    'nucleo' => $nucleo,
                ]);
            }

            return redirect()->route('nucleos.index')
                ->with('success', 'Núcleo criado com sucesso!');
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

    public function update(Request $request, Entidade $nucleo)
    {
        $this->authorize('update', $nucleo);

        try {
            $validated = $request->validate([
                'entidade_pai_id' => 'required|exists:entidades,id',
                'nome' => 'required|string|max:255',
                'email' => 'nullable|email',
                'ativo' => 'boolean',
            ]);

            $nucleo->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Núcleo atualizado com sucesso!',
                    'nucleo' => $nucleo,
                ]);
            }

            return redirect()->route('nucleos.index')
                ->with('success', 'Núcleo atualizado com sucesso!');
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

    public function destroy(Entidade $nucleo): RedirectResponse
    {
        $this->authorize('delete', $nucleo);
        $nucleo->delete();

        return redirect()->route('nucleos.index')
            ->with('success', 'Núcleo deletado com sucesso!');
    }

    protected function getModel()
    {
        return Entidade::class;
    }
}
