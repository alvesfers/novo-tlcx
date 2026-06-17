<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinanceiroCategoriaRequest;
use App\Http\Requests\UpdateFinanceiroCategoriaRequest;
use App\Models\FinanceiroCategoria;
use App\Traits\BulkDeleteable;

class FinanceiroCategoriaController extends Controller
{
    use BulkDeleteable;
    public function __construct()
    {
    }

    public function index()
    {
        $user = auth()->user();
        $categorias = FinanceiroCategoria::where('entidade_id', $user->entidade_id)
            ->orderBy('tipo')
            ->orderBy('nome')
            ->paginate(15);

        return view('financeiro.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return redirect()->route('financeiro-categorias.index');
    }

    public function store(StoreFinanceiroCategoriaRequest $request)
    {
        try {
            $user = auth()->user();

            $data = $request->validated();
            $data['entidade_id'] = $user->entidade_id;

            $categoria = FinanceiroCategoria::create($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria criada com sucesso!',
                    'categoria' => $categoria,
                ]);
            }

            return redirect()->route('financeiro-categorias.index')
                ->with('success', 'Categoria criada com sucesso');
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

    public function edit(FinanceiroCategoria $financeiro_categoria)
    {
        $this->authorize('update', $financeiro_categoria);
        return redirect()->route('financeiro-categorias.index');
    }

    public function update(UpdateFinanceiroCategoriaRequest $request, FinanceiroCategoria $financeiro_categoria)
    {
        $this->authorize('update', $financeiro_categoria);

        try {
            $financeiro_categoria->update($request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria atualizada com sucesso!',
                    'categoria' => $financeiro_categoria,
                ]);
            }

            return redirect()->route('financeiro-categorias.index')
                ->with('success', 'Categoria atualizada com sucesso');
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

    public function destroy(FinanceiroCategoria $financeiro_categoria)
    {
        $this->authorize('delete', $financeiro_categoria);
        $financeiro_categoria->delete();

        return redirect()->route('financeiro-categorias.index')
            ->with('success', 'Categoria deletada com sucesso');
    }

    protected function getModel()
    {
        return FinanceiroCategoria::class;
    }
}
