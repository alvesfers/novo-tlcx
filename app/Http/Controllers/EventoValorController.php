<?php

namespace App\Http\Controllers;

use App\Models\EventoValor;
use App\Models\Evento;
use App\Http\Requests\StoreEventoValorRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EventoValorController extends Controller
{
    public function index(Evento $evento): View
    {
        $valores = $evento->valores()->paginate(20);
        return view('evento-valores.index', compact('evento', 'valores'));
    }

    public function create(Evento $evento): View
    {
        return view('evento-valores.create', compact('evento'));
    }

    public function store(StoreEventoValorRequest $request): RedirectResponse
    {
        EventoValor::create($request->validated());
        return redirect()->route('eventos.valores.index', $request->evento_id)
            ->with('success', 'Valor criado com sucesso!');
    }

    public function edit(Evento $evento, EventoValor $valor): View
    {
        return view('evento-valores.edit', compact('evento', 'valor'));
    }

    public function update(StoreEventoValorRequest $request, EventoValor $valor): RedirectResponse
    {
        $valor->update($request->validated());
        return redirect()->route('eventos.valores.index', $valor->evento_id)
            ->with('success', 'Valor atualizado com sucesso!');
    }

    public function destroy(EventoValor $valor): RedirectResponse
    {
        $eventoId = $valor->evento_id;
        $valor->delete();
        return redirect()->route('eventos.valores.index', $eventoId)
            ->with('success', 'Valor deletado com sucesso!');
    }
}
