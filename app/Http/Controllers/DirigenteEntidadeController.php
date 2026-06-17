<?php

namespace App\Http\Controllers;

use App\Models\Dirigente;
use App\Models\DirigenteEntidade;
use App\Models\Entidade;
use App\Http\Requests\StoreDirigenteEntidadeRequest;
use App\Http\Requests\UpdateDirigenteEntidadeRequest;
use App\Services\DirigenteService;

class DirigenteEntidadeController extends Controller
{
    public function __construct(private DirigenteService $service) {}

    public function create(Dirigente $dirigente)
    {
        $this->authorize('view', $dirigente);

        $nucleos = Entidade::where('tipo_entidade', 'nucleo')->where('ativo', true)->get();
        $secretarias = Entidade::where('tipo_entidade', 'secretaria')->where('ativo', true)->get();
        $dioceses = Entidade::where('tipo_entidade', 'diocese')->where('ativo', true)->get();

        // Return JSON if requested (for modal)
        if (request()->expectsJson()) {
            return response()->json([
                'nucleos' => $nucleos,
                'secretarias' => $secretarias,
                'dioceses' => $dioceses,
            ]);
        }

        return view('dirigentes.vinculos.create', compact('dirigente', 'nucleos', 'secretarias', 'dioceses'));
    }

    public function store(StoreDirigenteEntidadeRequest $request)
    {
        $dirigente = Dirigente::findOrFail($request->dirigente_id);
        $this->authorize('manageVinculos', $dirigente);

        try {
            $vinculo = $this->service->adicionarVinculo($dirigente, $request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vínculo criado com sucesso.',
                    'vinculo' => $vinculo,
                ]);
            }
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['error' => $e->getMessage()],
                    'message' => $e->getMessage(),
                ], 422);
            }
            throw $e;
        }

        return redirect()->route('dirigentes.show', $vinculo->dirigente_id)
            ->with('success', 'Vínculo criado com sucesso.');
    }

    public function edit(Dirigente $dirigente, DirigenteEntidade $vinculo)
    {
        if ($vinculo->dirigente_id !== $dirigente->id) {
            abort(404);
        }

        $this->authorize('manageVinculos', $dirigente);

        if (request()->expectsJson()) {
            return response()->json([
                'vinculo' => $vinculo->load('entidade'),
            ]);
        }

        return view('dirigentes.vinculos.edit', compact('dirigente', 'vinculo'));
    }

    public function update(UpdateDirigenteEntidadeRequest $request, Dirigente $dirigente, DirigenteEntidade $vinculo)
    {
        if ($vinculo->dirigente_id !== $dirigente->id) {
            abort(404);
        }

        $this->authorize('manageVinculos', $dirigente);

        try {
            $this->service->atualizarVinculo($vinculo, $request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vínculo atualizado com sucesso.',
                    'vinculo' => $vinculo,
                ]);
            }
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['error' => $e->getMessage()],
                    'message' => $e->getMessage(),
                ], 422);
            }
            throw $e;
        }

        return redirect()->route('dirigentes.show', $dirigente)
            ->with('success', 'Vínculo atualizado com sucesso.');
    }

    public function destroy(Dirigente $dirigente, DirigenteEntidade $vinculo)
    {
        if ($vinculo->dirigente_id !== $dirigente->id) {
            abort(404);
        }

        $this->authorize('manageVinculos', $dirigente);

        try {
            $this->service->removerVinculo($vinculo);
            return redirect()->route('dirigentes.show', $dirigente)
                ->with('success', 'Vínculo removido com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('dirigentes.show', $dirigente)
                ->with('error', $e->getMessage());
        }
    }
}
