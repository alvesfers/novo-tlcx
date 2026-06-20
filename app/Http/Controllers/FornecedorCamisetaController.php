<?php

namespace App\Http\Controllers;

use App\Models\FornecedorCamiseta;
use App\Http\Requests\StoreFornecedorCamisetaRequest;
use App\Http\Requests\UpdateFornecedorCamisetaRequest;
use Illuminate\View\View;

class FornecedorCamisetaController extends Controller
{
    public function index(): View
    {
        $fornecedores = FornecedorCamiseta::withoutTrashed()->with('tipos')->paginate(15);
        return view('fornecedores-camisetas.index', compact('fornecedores'));
    }

    public function create(): View
    {
        return view('fornecedores-camisetas.create');
    }

    public function store(StoreFornecedorCamisetaRequest $request)
    {
        $fornecedor = FornecedorCamiseta::create($request->validated());

        if ($request->header('Accept') === 'application/json' || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Fornecedor criado com sucesso!',
                'fornecedor' => $fornecedor,
            ]);
        }

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

    public function update(UpdateFornecedorCamisetaRequest $request, FornecedorCamiseta $fornecedorCamiseta)
    {
        $fornecedorCamiseta->update($request->validated());

        if ($request->header('Accept') === 'application/json' || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Fornecedor atualizado com sucesso!',
                'fornecedor' => $fornecedorCamiseta,
            ]);
        }

        return redirect()->route('fornecedores-camisetas.show', $fornecedorCamiseta)
            ->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $fornecedorCamiseta = FornecedorCamiseta::findOrFail($id);
        $fornecedorCamiseta->delete();

        if (request()->header('Accept') === 'application/json' || request()->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Fornecedor deletado com sucesso!'
            ]);
        }

        return redirect()->route('fornecedores-camisetas.index')
            ->with('success', 'Fornecedor deletado com sucesso!');
    }
}
