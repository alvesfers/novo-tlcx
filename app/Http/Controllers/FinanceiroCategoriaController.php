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
        return view('financeiro.categorias.create');
    }

    public function store(StoreFinanceiroCategoriaRequest $request)
    {
        $user = auth()->user();

        $data = $request->validated();
        $data['entidade_id'] = $user->entidade_id;

        FinanceiroCategoria::create($data);

        return redirect()->route('financeiro-categorias.index')
            ->with('success', 'Categoria criada com sucesso');
    }

    public function edit(FinanceiroCategoria $financeiro_categoria)
    {
        $this->authorize('update', $financeiro_categoria);
        return view('financeiro.categorias.edit', ['categoria' => $financeiro_categoria]);
    }

    public function update(UpdateFinanceiroCategoriaRequest $request, FinanceiroCategoria $financeiro_categoria)
    {
        $this->authorize('update', $financeiro_categoria);
        $financeiro_categoria->update($request->validated());

        return redirect()->route('financeiro-categorias.index')
            ->with('success', 'Categoria atualizada com sucesso');
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
