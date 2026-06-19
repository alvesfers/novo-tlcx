<?php

namespace App\Http\Controllers;

use App\Models\FornecedorCamisetaTamanho;
use App\Models\FornecedorCamisetaTipo;
use App\Http\Requests\StoreFornecedorCamisetaTamanhoRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FornecedorCamisetaTamanhoController extends Controller
{
    public function index(FornecedorCamisetaTipo $tipo): View
    {
        $tamanhos = $tipo->tamanhos()->paginate(20);
        return view('fornecedor-camiseta-tamanhos.index', compact('tipo', 'tamanhos'));
    }

    public function create(FornecedorCamisetaTipo $tipo): View
    {
        return view('fornecedor-camiseta-tamanhos.create', compact('tipo'));
    }

    public function store(StoreFornecedorCamisetaTamanhoRequest $request): RedirectResponse
    {
        FornecedorCamisetaTamanho::create($request->validated());
        $tipo = FornecedorCamisetaTipo::find($request->fornecedor_camiseta_tipo_id);
        return redirect()->route('fornecedores-camisetas.tipos.tamanhos.index', [
            $tipo->fornecedor_id,
            $request->fornecedor_camiseta_tipo_id
        ])->with('success', 'Tamanho criado com sucesso!');
    }

    public function edit(FornecedorCamisetaTipo $tipo, FornecedorCamisetaTamanho $tamanho): View
    {
        return view('fornecedor-camiseta-tamanhos.edit', compact('tipo', 'tamanho'));
    }

    public function update(StoreFornecedorCamisetaTamanhoRequest $request, FornecedorCamisetaTamanho $tamanho): RedirectResponse
    {
        $tamanho->update($request->validated());
        $tipo = $tamanho->tipo;
        return redirect()->route('fornecedores-camisetas.tipos.tamanhos.index', [
            $tipo->fornecedor_id,
            $tipo->id
        ])->with('success', 'Tamanho atualizado com sucesso!');
    }

    public function destroy(FornecedorCamisetaTamanho $tamanho): RedirectResponse
    {
        $tipo = $tamanho->tipo;
        $tamanho->delete();
        return redirect()->route('fornecedores-camisetas.tipos.tamanhos.index', [
            $tipo->fornecedor_id,
            $tipo->id
        ])->with('success', 'Tamanho deletado com sucesso!');
    }
}
