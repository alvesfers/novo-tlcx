<?php

namespace App\Http\Controllers;

use App\Models\BarzinhoCombo;
use App\Models\Barzinho;
use App\Http\Requests\StoreBarzinhoCombRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BarzinhoCombController extends Controller
{
    public function index(Barzinho $barzinho): View
    {
        $combos = $barzinho->combos()->paginate(20);
        return view('barzinho-combos.index', compact('barzinho', 'combos'));
    }

    public function create(Barzinho $barzinho): View
    {
        return view('barzinho-combos.create', compact('barzinho'));
    }

    public function store(StoreBarzinhoCombRequest $request): RedirectResponse
    {
        BarzinhoCombo::create($request->validated());
        return redirect()->route('barzinhos.combos.index', $request->barzinho_id)
            ->with('success', 'Combo criado com sucesso!');
    }

    public function edit(Barzinho $barzinho, BarzinhoCombo $combo): View
    {
        return view('barzinho-combos.edit', compact('barzinho', 'combo'));
    }

    public function update(StoreBarzinhoCombRequest $request, BarzinhoCombo $combo): RedirectResponse
    {
        $combo->update($request->validated());
        return redirect()->route('barzinhos.combos.index', $combo->barzinho_id)
            ->with('success', 'Combo atualizado com sucesso!');
    }

    public function destroy(BarzinhoCombo $combo): RedirectResponse
    {
        $barzinhoId = $combo->barzinho_id;
        $combo->delete();
        return redirect()->route('barzinhos.combos.index', $barzinhoId)
            ->with('success', 'Combo deletado com sucesso!');
    }
}
