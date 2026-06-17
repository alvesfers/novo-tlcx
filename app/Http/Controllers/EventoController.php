<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Models\Evento;
use App\Models\TipoEvento;
use App\Models\Entidade;
use App\Services\EventoService;
use App\Traits\BulkDeleteable;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    use BulkDeleteable;
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

        $tiposEvento = TipoEvento::ativos()->get();
        $entidades = auth()->user()->isAdmin()
            ? Entidade::ativas()->get()
            : (auth()->user()->isDiocese()
                ? Entidade::where('id', auth()->user()->entidade_id)
                    ->orWhere('entidade_pai_id', auth()->user()->entidade_id)
                    ->get()
                : Entidade::where('id', auth()->user()->entidade_id)->get()
            );

        return view('eventos.index', compact('eventos', 'tiposEvento', 'entidades'));
    }

    public function create()
    {
        $this->authorize('create', Evento::class);
        return redirect()->route('eventos.index');
    }

    public function store(StoreEventoRequest $request)
    {
        $this->authorize('create', Evento::class);

        try {
            $evento = $this->eventoService->criar($request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Evento criado com sucesso!',
                    'evento' => $evento,
                ]);
            }

            return redirect()->route('eventos.show', $evento)
                ->with('success', 'Evento criado com sucesso');
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
        return redirect()->route('eventos.index');
    }

    public function update(UpdateEventoRequest $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        try {
            $this->eventoService->atualizar($evento, $request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Evento atualizado com sucesso!',
                    'evento' => $evento,
                ]);
            }

            return redirect()->route('eventos.show', $evento)
                ->with('success', 'Evento atualizado com sucesso');
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

    public function destroy(Evento $evento)
    {
        $this->authorize('delete', $evento);

        $evento->delete();

        return redirect()->route('eventos.index')
            ->with('success', 'Evento deletado com sucesso');
    }

    protected function getModel()
    {
        return Evento::class;
    }
}
