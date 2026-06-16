<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Models\Evento;
use App\Models\TipoEvento;
use App\Models\Entidade;
use App\Services\EventoService;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function __construct(private EventoService $eventoService) {}

    public function index()
    {
        $this->authorize('viewAny', Evento::class);

        $user = auth()->user();
        $query = Evento::with('tipoEvento', 'entidadeCriadora');

        if ($user->isAdmin()) {
            // Admin vê todos
        } elseif ($user->isDiocese()) {
            // Diocese vê eventos da sua estrutura
            $entidadeId = $user->entidade_id;
            $filhasIds = Entidade::where('entidade_pai_id', $entidadeId)
                ->pluck('id')
                ->push($entidadeId);

            $query->whereIn('entidade_criadora_id', $filhasIds);
        } elseif ($user->isNucleo() || $user->isSecretaria()) {
            // Nucleo/Secretaria vê eventos em que participa ou criou
            $entidadeId = $user->entidade_id;
            $query->where(function ($q) use ($entidadeId) {
                $q->where('entidade_criadora_id', $entidadeId)
                    ->orWhereHas('entidades', function ($sq) use ($entidadeId) {
                        $sq->where('entidade_id', $entidadeId);
                    });
            });
        }

        $eventos = $query->orderBy('data_inicio', 'desc')->paginate(15);
        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        $this->authorize('create', Evento::class);

        $tiposEvento = TipoEvento::ativos()->get();
        $entidades = auth()->user()->isAdmin()
            ? Entidade::ativas()->get()
            : (auth()->user()->isDiocese()
                ? Entidade::where('id', auth()->user()->entidade_id)
                    ->orWhere('entidade_pai_id', auth()->user()->entidade_id)
                    ->get()
                : Entidade::where('id', auth()->user()->entidade_id)->get()
            );

        return view('eventos.create', compact('tiposEvento', 'entidades'));
    }

    public function store(StoreEventoRequest $request)
    {
        $this->authorize('create', Evento::class);

        $evento = $this->eventoService->criar($request->validated());

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Evento criado com sucesso');
    }

    public function show(Evento $evento)
    {
        $this->authorize('view', $evento);

        $evento->load([
            'tipoEvento',
            'entidadeCriadora',
            'eventoEntidades.entidade',
            'participantes.dirigente',
            'participantes.participanteExterno',
        ]);

        return view('eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        $this->authorize('update', $evento);

        $tiposEvento = TipoEvento::ativos()->get();
        $entidades = auth()->user()->isAdmin()
            ? Entidade::ativas()->get()
            : (auth()->user()->isDiocese()
                ? Entidade::where('id', auth()->user()->entidade_id)
                    ->orWhere('entidade_pai_id', auth()->user()->entidade_id)
                    ->get()
                : Entidade::where('id', auth()->user()->entidade_id)->get()
            );

        return view('eventos.edit', compact('evento', 'tiposEvento', 'entidades'));
    }

    public function update(UpdateEventoRequest $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $this->eventoService->atualizar($evento, $request->validated());

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Evento atualizado com sucesso');
    }

    public function destroy(Evento $evento)
    {
        $this->authorize('delete', $evento);

        $evento->delete();

        return redirect()->route('eventos.index')
            ->with('success', 'Evento deletado com sucesso');
    }
}
