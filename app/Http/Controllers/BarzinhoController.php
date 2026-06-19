<?php

namespace App\Http\Controllers;

use App\Models\Barzinho;
use App\Models\Evento;
use App\Http\Requests\StoreBarzinhoRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BarzinhoController extends Controller
{
    public function index(): View
    {
        $barzinhos = Barzinho::with('evento')->paginate(15);
        return view('barzinhos.index', compact('barzinhos'));
    }

    public function create(): View
    {
        return view('barzinhos.create');
    }

    public function store(StoreBarzinhoRequest $request): RedirectResponse
    {
        Barzinho::create($request->validated());
        return redirect()->route('barzinhos.index')
            ->with('success', 'Barzinho criado com sucesso!');
    }

    public function show(Barzinho $barzinho): View
    {
        $barzinho->load(['produtos', 'combos', 'vendas']);
        return view('barzinhos.show', compact('barzinho'));
    }

    public function edit(Barzinho $barzinho): View
    {
        return view('barzinhos.edit', compact('barzinho'));
    }

    public function update(StoreBarzinhoRequest $request, Barzinho $barzinho): RedirectResponse
    {
        $barzinho->update($request->validated());
        return redirect()->route('barzinhos.show', $barzinho)
            ->with('success', 'Barzinho atualizado com sucesso!');
    }

    public function destroy(Barzinho $barzinho): RedirectResponse
    {
        $barzinho->delete();
        return redirect()->route('barzinhos.index')
            ->with('success', 'Barzinho deletado com sucesso!');
    }
}
