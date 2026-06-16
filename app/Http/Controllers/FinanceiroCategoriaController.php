<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinanceiroCategoriaRequest;
use App\Http\Requests\UpdateFinanceiroCategoriaRequest;
use App\Models\FinanceiroCategoria;
use Illuminate\Http\Request;

class FinanceiroCategoriaController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $user = auth()->user();
        $query = FinanceiroCategoria::where('entidade_id', $user->entidade_id);

        // Diocese vê categorias de filhos também
        if ($user->isDiocese()) {
            $filhasIds = \App\Models\Entidade::where('entidade_pai_id', $user->entidade_id)
                ->pluck('id')
                ->toArray();
            $query = FinanceiroCategoria::whereIn('entidade_id', array_merge([$user->entidade_id], $filhasIds));
        }

        $categorias = $query->orderBy('tipo')->orderBy('nome')->paginate(15);
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
}
