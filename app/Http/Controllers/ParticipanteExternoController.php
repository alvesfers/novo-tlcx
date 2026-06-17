<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipanteExternoRequest;
use App\Http\Requests\UpdateParticipanteExternoRequest;
use App\Models\ParticipanteExterno;
use App\Traits\BulkDeleteable;

class ParticipanteExternoController extends Controller
{
    use BulkDeleteable;
    public function index()
    {
        $participantes = ParticipanteExterno::paginate(15);
        return view('participante-externos.index', compact('participantes'));
    }

    public function create()
    {
        return view('participante-externos.create');
    }

    public function store(StoreParticipanteExternoRequest $request)
    {
        ParticipanteExterno::create($request->validated());

        return redirect()->route('participante-externos.index')
            ->with('success', 'Participante externo criado com sucesso');
    }

    public function show(ParticipanteExterno $participanteExterno)
    {
        $participanteExterno->load('eventoParticipantes.evento');
        return view('participante-externos.show', compact('participanteExterno'));
    }

    public function edit(ParticipanteExterno $participanteExterno)
    {
        return view('participante-externos.edit', compact('participanteExterno'));
    }

    public function update(UpdateParticipanteExternoRequest $request, ParticipanteExterno $participanteExterno)
    {
        $participanteExterno->update($request->validated());

        return redirect()->route('participante-externos.show', $participanteExterno)
            ->with('success', 'Participante externo atualizado com sucesso');
    }

    public function destroy(ParticipanteExterno $participanteExterno)
    {
        $participanteExterno->delete();

        return redirect()->route('participante-externos.index')
            ->with('success', 'Participante externo deletado com sucesso');
    }

    protected function getModel()
    {
        return ParticipanteExterno::class;
    }
}
