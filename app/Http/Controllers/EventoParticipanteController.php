<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoParticipanteRequest;
use App\Models\Evento;
use App\Models\EventoParticipante;
use App\Models\Dirigente;
use App\Models\ParticipanteExterno;
use App\Services\EventoService;
use App\Enums\TipoParticipanteEvento;

class EventoParticipanteController extends Controller
{
    public function __construct(private EventoService $eventoService) {}

    public function create(Evento $evento)
    {
        $this->authorize('manageParticipantes', $evento);

        $entidadesEvento = $evento->entidades()->pluck('id');
        $dirigentes = Dirigente::ativos()
            ->whereHas('vinculos', function ($q) use ($entidadesEvento) {
                $q->whereIn('entidade_id', $entidadesEvento)
                    ->where('ativo', true);
            })
            ->get();

        $externos = ParticipanteExterno::all();

        if (request()->expectsJson()) {
            return response()->json([
                'dirigentes' => $dirigentes,
                'externos' => $externos,
            ]);
        }

        return view('eventos.participantes.create', compact('evento', 'dirigentes', 'externos'));
    }

    public function store(StoreEventoParticipanteRequest $request)
    {
        $evento = Evento::findOrFail($request->evento_id);
        $this->authorize('manageParticipantes', $evento);

        try {
            if ($request->tipo_participante === TipoParticipanteEvento::Dirigente->value) {
                $dirigente = Dirigente::findOrFail($request->dirigente_id);
                $this->eventoService->adicionarDirigente($evento, $dirigente, $request->observacao);
            } else {
                $externo = ParticipanteExterno::findOrFail($request->participante_externo_id);
                $this->eventoService->adicionarExterno($evento, $externo, $request->observacao);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Participante adicionado com sucesso',
                ]);
            }

            return redirect()->route('eventos.show', $evento)
                ->with('success', 'Participante adicionado com sucesso');
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['error' => $e->getMessage()],
                    'message' => $e->getMessage(),
                ], 422);
            }
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Evento $evento, EventoParticipante $eventoParticipante)
    {
        $this->authorize('manageParticipantes', $evento);

        $this->eventoService->removerParticipante($eventoParticipante);

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Participante removido com sucesso');
    }

    public function marcarPresenca(Evento $evento, EventoParticipante $eventoParticipante)
    {
        $this->authorize('manageParticipantes', $evento);

        $this->eventoService->marcarPresenca($eventoParticipante);

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Presença marcada com sucesso');
    }
}
