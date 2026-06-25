<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoParticipanteRequest;
use App\Models\Evento;
use App\Models\EventoParticipante;
use App\Models\Dirigente;
use App\Models\ParticipanteExterno;
use App\Services\EventoService;
use App\Enums\TipoParticipanteEvento;
use Illuminate\Http\Request;

class EventoParticipanteController extends Controller
{
    public function __construct(private EventoService $eventoService) {}

    public function create(Evento $evento)
    {
        $this->authorize('manageParticipantes', $evento);

        $entidadesEvento = $evento->entidades()->pluck('entidades.id');
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
        $eventoParticipante->marcarPresenca();
        return back()->with('success', 'Presença marcada com sucesso');
    }

    public function desmarcarPresenca(Evento $evento, EventoParticipante $eventoParticipante)
    {
        $this->authorize('manageParticipantes', $evento);
        $eventoParticipante->desmarcarPresenca();
        return back()->with('success', 'Presença desmarcada');
    }

    public function dirigentesPorEntidade(Evento $evento, Request $request)
    {
        $this->authorize('view', $evento);

        $entidade_id = $request->query('entidade_id');

        $jaParticipam = EventoParticipante::where('evento_id', $evento->id)
            ->where('tipo_participante', 'dirigente')
            ->pluck('dirigente_id');

        $dirigentes = Dirigente::where('ativo', true)
            ->whereNotIn('id', $jaParticipam)
            ->whereHas('vinculos', function ($q) use ($entidade_id) {
                $q->where('entidade_id', $entidade_id)->where('ativo', true);
            })
            ->select('id', 'nome', 'email', 'apelido')
            ->orderBy('nome')
            ->get();

        return response()->json($dirigentes);
    }

    public function storeLote(Evento $evento, Request $request)
    {
        $this->authorize('manageParticipantes', $evento);

        $ids = $request->input('dirigente_ids', []);
        $adicionados = 0;

        foreach ($ids as $dirigente_id) {
            $jaParticipa = EventoParticipante::where('evento_id', $evento->id)
                ->where('dirigente_id', $dirigente_id)
                ->where('tipo_participante', TipoParticipanteEvento::Dirigente->value)
                ->exists();

            if (!$jaParticipa && Dirigente::where('id', $dirigente_id)->where('ativo', true)->exists()) {
                EventoParticipante::create([
                    'evento_id'              => $evento->id,
                    'tipo_participante'      => TipoParticipanteEvento::Dirigente->value,
                    'dirigente_id'           => $dirigente_id,
                    'presenca'               => false,
                    'inscricao_opcao_id'     => $request->input('inscricao_opcao_id') ?: null,
                    'inscricao_camiseta_tipo' => $request->input('inscricao_camiseta_tipo') ?: null,
                ]);
                $adicionados++;
            }
        }

        return response()->json([
            'success'    => true,
            'adicionados' => $adicionados,
            'message'    => "$adicionados participante(s) adicionado(s) com sucesso!",
        ]);
    }

    public function marcarPresencaLote(Evento $evento, Request $request)
    {
        $this->authorize('manageParticipantes', $evento);

        $ids = $request->input('ids', []);
        $updated = 0;

        foreach ($ids as $id) {
            $participante = EventoParticipante::where('evento_id', $evento->id)
                ->where('id', $id)
                ->first();

            if ($participante) {
                $participante->marcarPresenca();
                $updated++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "✅ Presença marcada para $updated participante(s)"
        ]);
    }

    public function removerLote(Evento $evento, Request $request)
    {
        $this->authorize('manageParticipantes', $evento);

        $ids = $request->input('ids', []);
        $deleted = 0;

        foreach ($ids as $id) {
            $participante = EventoParticipante::where('evento_id', $evento->id)
                ->where('id', $id)
                ->first();

            if ($participante) {
                $participante->delete();
                $deleted++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "✅ $deleted participante(s) removido(s)"
        ]);
    }
}
