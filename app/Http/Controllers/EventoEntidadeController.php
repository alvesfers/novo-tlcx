<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoEntidadeRequest;
use App\Models\Evento;
use App\Models\EventoEntidade;
use App\Models\Entidade;
use App\Services\EventoService;

class EventoEntidadeController extends Controller
{
    public function __construct(private EventoService $eventoService) {}

    public function create(Evento $evento)
    {
        $this->authorize('manageParticipantes', $evento);

        $entidadesCriadora = $evento->entidadeCriadora;
        $entidadeJaParticipa = $evento->entidades()->pluck('id')->toArray();

        $entidades = Entidade::ativas()
            ->whereNotIn('id', $entidadeJaParticipa)
            ->get();

        return view('eventos.entidades.create', compact('evento', 'entidades'));
    }

    public function store(StoreEventoEntidadeRequest $request)
    {
        $evento = Evento::findOrFail($request->evento_id);
        $this->authorize('manageParticipantes', $evento);

        $entidade = Entidade::findOrFail($request->entidade_id);

        try {
            $this->eventoService->adicionarEntidade(
                $evento,
                $entidade,
                $request->tipo_participacao
            );

            return redirect()->route('eventos.show', $evento)
                ->with('success', 'Entidade adicionada com sucesso');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['entidade_id' => $e->getMessage()]);
        }
    }

    public function destroy(Evento $evento, EventoEntidade $eventoEntidade)
    {
        $this->authorize('manageParticipantes', $evento);

        try {
            $this->eventoService->removerEntidade($eventoEntidade);

            return redirect()->route('eventos.show', $evento)
                ->with('success', 'Entidade removida com sucesso');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
