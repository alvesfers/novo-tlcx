<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoEventoRequest;
use App\Http\Requests\UpdateTipoEventoRequest;
use App\Models\TipoEvento;
use Illuminate\Http\Request;

class TipoEventoController extends Controller
{
    public function index()
    {
        $tiposEvento = TipoEvento::paginate(15);
        return view('tipo-eventos.index', compact('tiposEvento'));
    }

    public function create()
    {
        return view('tipo-eventos.create');
    }

    public function store(StoreTipoEventoRequest $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem criar tipos de eventos');
        }

        TipoEvento::create($request->validated());

        return redirect()->route('tipo-eventos.index')
            ->with('success', 'Tipo de evento criado com sucesso');
    }

    public function show(TipoEvento $tipoEvento)
    {
        return view('tipo-eventos.show', compact('tipoEvento'));
    }

    public function edit(TipoEvento $tipoEvento)
    {
        return view('tipo-eventos.edit', compact('tipoEvento'));
    }

    public function update(UpdateTipoEventoRequest $request, TipoEvento $tipoEvento)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem editar tipos de eventos');
        }

        $tipoEvento->update($request->validated());

        return redirect()->route('tipo-eventos.show', $tipoEvento)
            ->with('success', 'Tipo de evento atualizado com sucesso');
    }

    public function destroy(TipoEvento $tipoEvento)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem deletar tipos de eventos');
        }

        $tipoEvento->delete();

        return redirect()->route('tipo-eventos.index')
            ->with('success', 'Tipo de evento deletado com sucesso');
    }
}
