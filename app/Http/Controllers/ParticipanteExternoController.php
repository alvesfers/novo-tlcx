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
        return redirect()->route('participante-externos.index');
    }

    public function store(StoreParticipanteExternoRequest $request)
    {
        try {
            $participante = ParticipanteExterno::create($request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Participante externo criado com sucesso!',
                    'participante' => $participante,
                ]);
            }

            return redirect()->route('participante-externos.index')
                ->with('success', 'Participante externo criado com sucesso');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Erro na validação dos dados',
                ], 422);
            }
            throw $e;
        }
    }

    public function show(ParticipanteExterno $participanteExterno)
    {
        $participanteExterno->load('eventoParticipantes.evento');
        return view('participante-externos.show', compact('participanteExterno'));
    }

    public function edit(ParticipanteExterno $participanteExterno)
    {
        return redirect()->route('participante-externos.index');
    }

    public function update(UpdateParticipanteExternoRequest $request, ParticipanteExterno $participanteExterno)
    {
        try {
            $participanteExterno->update($request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Participante externo atualizado com sucesso!',
                    'participante' => $participanteExterno,
                ]);
            }

            return redirect()->route('participante-externos.show', $participanteExterno)
                ->with('success', 'Participante externo atualizado com sucesso');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Erro na validação dos dados',
                ], 422);
            }
            throw $e;
        }
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
