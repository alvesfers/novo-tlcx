<?php

namespace App\Http\Controllers;

use App\Models\BarzinhoProduto;
use App\Models\Barzinho;
use App\Http\Requests\StoreBarzinhoProdutoRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BarzinhoProdutoController extends Controller
{
    public function index(Barzinho $barzinho): View
    {
        $produtos = $barzinho->produtos()->paginate(20);
        return view('barzinho-produtos.index', compact('barzinho', 'produtos'));
    }

    public function create(Barzinho $barzinho): View
    {
        return view('barzinho-produtos.create', compact('barzinho'));
    }

    public function store(StoreBarzinhoProdutoRequest $request): RedirectResponse
    {
        $produto = BarzinhoProduto::create($request->validated());
        return redirect()->route('barzinhos.produtos.index', $produto->barzinho_id)
            ->with('success', 'Produto criado com sucesso!');
    }

    public function edit(Barzinho $barzinho, BarzinhoProduto $produto): View
    {
        return view('barzinho-produtos.edit', compact('barzinho', 'produto'));
    }

    public function update(StoreBarzinhoProdutoRequest $request, BarzinhoProduto $produto): RedirectResponse
    {
        $produto->update($request->validated());
        return redirect()->route('barzinhos.produtos.index', $produto->barzinho_id)
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(BarzinhoProduto $produto): RedirectResponse
    {
        $barzinhoId = $produto->barzinho_id;
        $produto->delete();
        return redirect()->route('barzinhos.produtos.index', $barzinhoId)
            ->with('success', 'Produto deletado com sucesso!');
    }
}
