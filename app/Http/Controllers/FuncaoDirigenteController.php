<?php

namespace App\Http\Controllers;

use App\Models\FuncaoDirigente;
use App\Http\Requests\StoreFuncaoDirigenteRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FuncaoDirigenteController extends Controller
{
    public function index(): View
    {
        $funcoes = FuncaoDirigente::paginate(20);
        return view('funcoes-dirigentes.index', compact('funcoes'));
    }

    public function create(): View
    {
        return view('funcoes-dirigentes.create');
    }

    public function store(StoreFuncaoDirigenteRequest $request): RedirectResponse
    {
        FuncaoDirigente::create($request->validated());
        return redirect()->route('funcoes-dirigentes.index')
            ->with('success', 'Função de dirigente criada com sucesso!');
    }

    public function edit(FuncaoDirigente $funcoesDirigente): View
    {
        return view('funcoes-dirigentes.edit', compact('funcoesDirigente'));
    }

    public function update(StoreFuncaoDirigenteRequest $request, FuncaoDirigente $funcoesDirigente): RedirectResponse
    {
        $funcoesDirigente->update($request->validated());
        return redirect()->route('funcoes-dirigentes.index')
            ->with('success', 'Função de dirigente atualizada com sucesso!');
    }

    public function destroy(FuncaoDirigente $funcoesDirigente): RedirectResponse
    {
        $funcoesDirigente->delete();
        return redirect()->route('funcoes-dirigentes.index')
            ->with('success', 'Função de dirigente deletada com sucesso!');
    }
}
