<?php

namespace App\Http\Controllers;

use App\Models\FornecedorCamiseta;
use App\Http\Requests\StoreFornecedorCamisetaRequest;
use App\Http\Requests\UpdateFornecedorCamisetaRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FornecedorCamisetaController extends Controller
{
    public function index(): View
    {
        $fornecedores = FornecedorCamiseta::with('tipos')->paginate(15);
        return view('fornecedores-camisetas.index', compact('fornecedores'));
    }

    public function create(): View
    {
        return view('fornecedores-camisetas.create');
    }

    public function store(StoreFornecedorCamisetaRequest $request): RedirectResponse
    {
        FornecedorCamiseta::create($request->validated());
        return redirect()->route('fornecedores-camisetas.index')
            ->with('success', 'Fornecedor de camisetas criado com sucesso!');
    }

    public function show(FornecedorCamiseta $fornecedorCamiseta): View
    {
        $fornecedorCamiseta->load('tipos.tamanhos');
        return view('fornecedores-camisetas.show', compact('fornecedorCamiseta'));
    }

    public function edit(FornecedorCamiseta $fornecedorCamiseta): View
    {
        return view('fornecedores-camisetas.edit', compact('fornecedorCamiseta'));
    }

    public function update(UpdateFornecedorCamisetaRequest $request, FornecedorCamiseta $fornecedorCamiseta): RedirectResponse
    {
        $fornecedorCamiseta->update($request->validated());
        return redirect()->route('fornecedores-camisetas.show', $fornecedorCamiseta)
            ->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(FornecedorCamiseta $fornecedorCamiseta): RedirectResponse
    {
        $fornecedorCamiseta->delete();
        return redirect()->route('fornecedores-camisetas.index')
            ->with('success', 'Fornecedor deletado com sucesso!');
    }
}
